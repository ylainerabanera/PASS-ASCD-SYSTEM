<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Set;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use ZipArchive;

class ExportController extends Controller
{
    public function facultyPdf(Faculty $faculty)
    {
        $schedules = Schedule::with(['subject', 'set.course', 'room'])
            ->where('faculty_id', $faculty->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $pdf = $this->makeTimetablePdf([
            'title' => 'Faculty Schedule',
            'subtitle' => 'Faculty: ' . $faculty->name . ' | Total Classes: ' . $schedules->count(),
            'schedules' => $schedules,
        ], $schedules);

        return $pdf->stream('faculty-' . $faculty->id . '-schedule.pdf');
    }

    public function roomPdf(Room $room)
    {
        $schedules = Schedule::with(['subject', 'set.course', 'faculty'])
            ->where('room_id', $room->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $pdf = $this->makeTimetablePdf([
            'title' => 'Room Schedule',
            'subtitle' => 'Room: ' . $room->room_name . ' | Building: ' . $room->building_name . ' | Capacity: ' . $room->capacity . ' | Total Classes: ' . $schedules->count(),
            'schedules' => $schedules,
        ], $schedules);

        return $pdf->stream('room-' . $room->id . '-schedule.pdf');
    }

    public function coursePdf(Course $course)
    {
        $schedules = Schedule::with(['subject', 'set.course', 'faculty', 'room'])
            ->whereHas('set', fn($query) => $query->where('course_id', $course->id))
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $pdf = $this->makeTimetablePdf([
            'title' => 'Course Schedule',
            'subtitle' => 'Course: ' . $course->name . ' | Total Classes: ' . $schedules->count(),
            'schedules' => $schedules,
        ], $schedules);

        return $pdf->stream('course-' . $course->id . '-schedule.pdf');
    }

    public function courseSetPdf(Course $course, Set $set)
    {
        if ($set->course_id !== $course->id) {
            abort(404);
        }

        $schedules = Schedule::with(['subject', 'set.course', 'faculty', 'room'])
            ->where('set_id', $set->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $pdf = $this->makeTimetablePdf([
            'title' => 'Course/Set Schedule',
            'subtitle' => $this->buildCourseYearSubtitle($set, $schedules->count(), 'Total Classes'),
            'schedules' => $schedules,
        ], $schedules);

        return $pdf->stream('course-' . $course->id . '-set-' . $set->id . '-schedule.pdf');
    }

    public function setPdf(Set $set)
    {
        $schedules = Schedule::with(['subject', 'set.course', 'faculty', 'room'])
            ->where('set_id', $set->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $pdf = $this->makeTimetablePdf([
            'title' => 'Set Schedule',
            'subtitle' => $this->buildCourseYearSubtitle($set, $schedules->count(), 'Total Classes'),
            'schedules' => $schedules,
        ], $schedules);

        return $pdf->stream('set-' . $set->id . '-schedule.pdf');
    }

    public function batchFaculty()
    {
        $schedules = Schedule::with(['subject', 'set.course', 'room'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('faculty_id');

        return $this->batchZip('faculty', Faculty::query()->orderBy('name')->get(), function ($faculty) use ($schedules) {
            $facultySchedules = $schedules->get($faculty->id, collect());

            return $this->makeTimetablePdf([
                'title' => 'Faculty Schedule',
                'subtitle' => 'Faculty: ' . $faculty->name . ' | Total Classes: ' . $facultySchedules->count(),
                'schedules' => $facultySchedules,
            ], $facultySchedules)->output();
        });
    }

    public function batchCourse()
    {
        $schedules = Schedule::with(['subject', 'set.course', 'faculty', 'room'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy(fn ($schedule) => $schedule->set->course_id);

        return $this->batchZip('course', Course::query()->orderBy('name')->get(), function ($course) use ($schedules) {
            $courseSchedules = $schedules->get($course->id, collect());

            return $this->makeTimetablePdf([
                'title' => 'Course Schedule',
                'subtitle' => 'Course: ' . $course->name . ' | Total Classes: ' . $courseSchedules->count(),
                'schedules' => $courseSchedules,
            ], $courseSchedules)->output();
        });
    }

    public function batchRoom()
    {
        $schedules = Schedule::with(['subject', 'set.course', 'faculty'])
            ->whereNotNull('room_id')
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('room_id');

        return $this->batchZip('room', Room::query()->orderBy('building_name')->orderBy('room_name')->get(), function ($room) use ($schedules) {
            $roomSchedules = $schedules->get($room->id, collect());

            return $this->makeTimetablePdf([
                'title' => 'Room Schedule',
                'subtitle' => 'Room: ' . $room->room_name . ' | Building: ' . $room->building_name . ' | Capacity: ' . $room->capacity . ' | Total Classes: ' . $roomSchedules->count(),
                'schedules' => $roomSchedules,
            ], $roomSchedules)->output();
        });
    }

    // CSV export removed

    private function buildGrid($schedules): array
    {
        $days = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
        ];

        $start = Carbon::createFromTime(8, 0);
        $end = Carbon::createFromTime(20, 0);
        $slots = [];
        $current = $start->copy();

        while ($current->lt($end)) {
            $slotEnd = $current->copy()->addMinutes(15);
            $slots[] = [
                'start' => $current->format('H:i'),
                'end' => $slotEnd->format('H:i'),
                'label' => $current->format('g:i') . ' - ' . $slotEnd->format('g:i A'),
            ];
            $current = $slotEnd;
        }

        $grid = [];
        foreach ($days as $day) {
            $grid[$day] = array_fill(0, count($slots), ['type' => 'empty']);
        }

        foreach ($schedules as $schedule) {
            if (!isset($grid[$schedule->day])) {
                continue;
            }

            $startTime = Carbon::parse($schedule->start_time);
            $endTime = Carbon::parse($schedule->end_time);
            $startIndex = $start->diffInMinutes($startTime, false) / 15;
            $endIndex = $start->diffInMinutes($endTime, false) / 15;

            $startIndex = (int) $startIndex;
            $endIndex = (int) $endIndex;

            if ($startIndex < 0 || $endIndex > count($slots) || $endIndex <= $startIndex) {
                continue;
            }

            if ($grid[$schedule->day][$startIndex]['type'] !== 'empty') {
                continue;
            }

            $rowspan = $endIndex - $startIndex;
            $grid[$schedule->day][$startIndex] = [
                'type' => 'filled',
                'rowspan' => $rowspan,
                'schedule' => $schedule,
            ];

            for ($i = $startIndex + 1; $i < $endIndex; $i++) {
                $grid[$schedule->day][$i] = ['type' => 'skip'];
            }
        }

        return [
            'days' => $days,
            'slots' => $slots,
            'grid' => $grid,
        ];
    }

    private function buildCourseYearSubtitle(Set $set, int $count, string $countLabel): string
    {
        $subtitle = 'Course and Year: ' . $set->course->name . ' - ' . $this->formatYearLevel($set->year_level);

        if ($set->set_code) {
            $subtitle .= ' | Set: ' . $set->set_code;
        }

        return $subtitle . ' | ' . $countLabel . ': ' . $count;
    }

    private function makeTimetablePdf(array $data, $schedules)
    {
        ini_set('max_execution_time', '300');
        ini_set('memory_limit', '512M');

        return Pdf::loadView('exports.timetable_pdf', array_merge($data, $this->buildGrid($schedules)))
            ->setPaper([0, 0, 936, 612]);
    }

    private function formatYearLevel(int $yearLevel): string
    {
        return match ($yearLevel) {
            1 => '1st Year',
            2 => '2nd Year',
            3 => '3rd Year',
            4 => '4th Year',
            default => $yearLevel . 'th Year',
        };
    }

    private function batchZip(string $prefix, $items, callable $pdfCallback)
    {
        $tmpDir = storage_path('app/tmp');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0777, true);
        }

        $zipName = $prefix . '-schedules-' . now()->format('Ymd-His') . '.zip';
        $zipPath = $tmpDir . DIRECTORY_SEPARATOR . $zipName;

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            abort(500, 'Could not create zip file.');
        }

        ini_set('max_execution_time', '300');
        ini_set('memory_limit', '512M');

        foreach ($items as $item) {
            $pdfContent = $pdfCallback($item);

            $name = $item->name ?? ($item->building_name . ' ' . $item->room_name) ?? (string) $item->id;
            $safeName = preg_replace('/[^A-Za-z0-9\-_ ]/', '', (string) $name);
            $fileName = $prefix . '-' . trim(strtolower(str_replace(' ', '-', $safeName))) . '.pdf';
            $zip->addFromString($fileName, $pdfContent);
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}

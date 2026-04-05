<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Set;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;

class ExportController extends Controller
{
    public function facultyPdf(Faculty $faculty)
    {
        $schedules = Schedule::with(['subject', 'set.course', 'room'])
            ->where('faculty_id', $faculty->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $pdf = Pdf::loadView('exports.timetable_pdf', array_merge([
            'title' => 'Faculty Schedule',
            'subtitle' => 'Faculty: ' . $faculty->name . ' | Total Classes: ' . $schedules->count(),
            'schedules' => $schedules,
        ], $this->buildGrid($schedules)))->setPaper([0, 0, 936, 612]);

        return $pdf->stream('faculty-' . $faculty->id . '-schedule.pdf');
    }

    public function roomPdf(Room $room)
    {
        $schedules = Schedule::with(['subject', 'set.course', 'faculty'])
            ->where('room_id', $room->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $pdf = Pdf::loadView('exports.timetable_pdf', array_merge([
            'title' => 'Room Schedule',
            'subtitle' => 'Room: ' . $room->room_name . ' | Building: ' . $room->building_name . ' | Capacity: ' . $room->capacity . ' | Total Classes: ' . $schedules->count(),
            'schedules' => $schedules,
        ], $this->buildGrid($schedules)))->setPaper([0, 0, 936, 612]);

        return $pdf->stream('room-' . $room->id . '-schedule.pdf');
    }

    public function coursePdf(Course $course)
    {
        $schedules = Schedule::with(['subject', 'set.course', 'faculty', 'room'])
            ->whereHas('set', fn ($query) => $query->where('course_id', $course->id))
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $pdf = Pdf::loadView('exports.timetable_pdf', array_merge([
            'title' => 'Course Schedule',
            'subtitle' => 'Course: ' . $course->name . ' | Total Classes: ' . $schedules->count(),
            'schedules' => $schedules,
        ], $this->buildGrid($schedules)))->setPaper([0, 0, 936, 612]);

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

        $pdf = Pdf::loadView('exports.timetable_pdf', array_merge([
            'title' => 'Course/Set Schedule',
            'subtitle' => 'Course: ' . $course->name . ' | Set: ' . $set->display_name . ' | Total Classes: ' . $schedules->count(),
            'schedules' => $schedules,
        ], $this->buildGrid($schedules)))->setPaper([0, 0, 936, 612]);

        return $pdf->stream('course-' . $course->id . '-set-' . $set->id . '-schedule.pdf');
    }

    public function setPdf(Set $set)
    {
        $schedules = Schedule::with(['subject', 'set.course', 'faculty', 'room'])
            ->where('set_id', $set->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $pdf = Pdf::loadView('exports.timetable_pdf', array_merge([
            'title' => 'Set Schedule',
            'subtitle' => 'Set: ' . $set->display_name . ' | Total Classes: ' . $schedules->count(),
            'schedules' => $schedules,
        ], $this->buildGrid($schedules)))->setPaper([0, 0, 936, 612]);

        return $pdf->stream('set-' . $set->id . '-schedule.pdf');
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

            $startTime = Carbon::createFromFormat('H:i:s', $schedule->start_time);
            $endTime = Carbon::createFromFormat('H:i:s', $schedule->end_time);
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
}

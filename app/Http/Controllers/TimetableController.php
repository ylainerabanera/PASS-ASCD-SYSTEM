<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Set;
use Illuminate\Support\Carbon;

class TimetableController extends Controller
{
    public function index()
    {
        return view('timetables.index', [
            'faculties' => Faculty::query()->orderBy('name')->get(),
            'courses' => Course::query()->orderBy('name')->get(),
            'sets' => Set::with('course')->orderBy('course_id')->orderBy('year_level')->orderBy('set_code')->get(),
            'rooms' => Room::query()->orderBy('building_name')->orderBy('room_name')->get(),
            'onlineCount' => Schedule::query()->where('class_type', 'online')->count(),
        ]);
    }

    public function faculty(Faculty $faculty)
    {
        $schedules = Schedule::with(['subject', 'set.course', 'room'])
            ->where('faculty_id', $faculty->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('timetables.show', array_merge([
            'title' => 'Faculty Schedule',
            'schedules' => $schedules,
            'pdfUrl' => route('exports.faculty.pdf', $faculty),
            'subtitle' => 'Faculty: ' . $faculty->name . ' | Total Classes: ' . $schedules->count(),
        ], $this->buildGrid($schedules)));
    }

    public function room(Room $room)
    {
        $schedules = Schedule::with(['subject', 'set.course', 'faculty'])
            ->where('room_id', $room->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('timetables.show', array_merge([
            'title' => 'Room Schedule',
            'schedules' => $schedules,
            'pdfUrl' => route('exports.room.pdf', $room),
            'subtitle' => 'Room: ' . $room->room_name . ' | Building: ' . $room->building_name . ' | Capacity: ' . $room->capacity . ' | Total Classes: ' . $schedules->count(),
        ], $this->buildGrid($schedules)));
    }

    public function course(Course $course)
    {
        $schedules = Schedule::with(['subject', 'set.course', 'faculty', 'room'])
            ->whereHas('set', fn ($query) => $query->where('course_id', $course->id))
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $sets = Set::where('course_id', $course->id)
            ->orderBy('year_level')
            ->orderBy('set_code')
            ->get();

        return view('timetables.show', array_merge([
            'title' => 'Course Schedule',
            'schedules' => $schedules,
            'pdfUrl' => route('exports.course.pdf', $course),
            'setLinks' => $sets,
            'course' => $course,
            'subtitle' => 'Course: ' . $course->name . ' | Total Classes: ' . $schedules->count(),
        ], $this->buildGrid($schedules)));
    }

    public function courseSet(Course $course, Set $set)
    {
        if ($set->course_id !== $course->id) {
            abort(404);
        }

        $schedules = Schedule::with(['subject', 'set.course', 'faculty', 'room'])
            ->where('set_id', $set->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('timetables.show', array_merge([
            'title' => 'Course/Set Schedule',
            'schedules' => $schedules,
            'pdfUrl' => route('exports.course.set.pdf', [$course, $set]),
            'course' => $course,
            'subtitle' => $this->buildCourseYearSubtitle($set, $schedules->count(), 'Total Classes'),
        ], $this->buildGrid($schedules)));
    }

    public function courseSetOnline(Course $course, Set $set)
    {
        if ($set->course_id !== $course->id) {
            abort(404);
        }

        $schedules = Schedule::with(['subject', 'set.course', 'faculty'])
            ->where('set_id', $set->id)
            ->where('class_type', 'online')
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('timetables.show', array_merge([
            'title' => 'Online Course/Set Schedule',
            'schedules' => $schedules,
            'course' => $course,
            'subtitle' => $this->buildCourseYearSubtitle($set, $schedules->count(), 'Online Classes'),
        ], $this->buildGrid($schedules)));
    }

    public function set(Set $set)
    {
        $schedules = Schedule::with(['subject', 'set.course', 'faculty', 'room'])
            ->where('set_id', $set->id)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('timetables.show', array_merge([
            'title' => 'Set Schedule',
            'schedules' => $schedules,
            'pdfUrl' => route('exports.set.pdf', $set),
            'subtitle' => $this->buildCourseYearSubtitle($set, $schedules->count(), 'Total Classes'),
        ], $this->buildGrid($schedules)));
    }

    public function online()
    {
        $schedules = Schedule::with(['subject', 'set.course', 'faculty'])
            ->where('class_type', 'online')
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        return view('timetables.show', array_merge([
            'title' => 'Online Classes Schedule',
            'schedules' => $schedules,
            'subtitle' => 'Online Classes | Total Classes: ' . $schedules->count(),
        ], $this->buildGrid($schedules)));
    }

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

            if (!is_int($startIndex) || !is_int($endIndex)) {
                $startIndex = (int) $startIndex;
                $endIndex = (int) $endIndex;
            }

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
}

<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\Set;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    private array $days = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
    ];

    public function index()
    {
        $schedules = Schedule::with(['subject', 'set.course', 'faculty', 'room'])
            ->orderBy('id')
            ->get();

        return view('schedules.index', [
            'schedules' => $schedules,
        ]);
    }

    public function create()
    {
        return view('schedules.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validateSchedule($request);

        Schedule::create($data);

        return redirect()->route('schedules.index')->with('status', 'Schedule created successfully.');
    }

    public function edit(Schedule $schedule)
    {
        return view('schedules.edit', $this->formData($schedule));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $data = $this->validateSchedule($request, $schedule);

        $schedule->update($data);

        return redirect()->route('schedules.index')->with('status', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedules.index')->with('status', 'Schedule deleted successfully.');
    }

    private function formData(?Schedule $schedule = null): array
    {
        return [
            'schedule' => $schedule,
            'subjects' => Subject::query()->orderBy('subject_code')->get(),
            'sets' => Set::with('course')->orderBy('course_id')->orderBy('year_level')->orderBy('set_code')->get(),
            'faculties' => Faculty::query()->orderBy('name')->get(),
            'rooms' => Room::query()->orderBy('building_name')->orderBy('room_name')->get(),
            'days' => $this->days,
        ];
    }

    private function validateSchedule(Request $request, ?Schedule $current = null): array
    {
        $validator = Validator::make($request->all(), [
            'subject_id' => ['required', 'exists:subjects,id'],
            'set_id' => ['required', 'exists:sets,id'],
            'faculty_id' => ['required', 'exists:faculties,id'],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'day' => ['required', 'string', 'in:' . implode(',', $this->days)],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'class_type' => ['required', 'string', 'in:face_to_face,online'],
        ]);

        $validator->after(function ($validator) use ($request) {
            if (!$request->filled(['start_time', 'end_time'])) {
                return;
            }

            $startMinutes = $this->minutesFromTime($request->input('start_time'));
            $endMinutes = $this->minutesFromTime($request->input('end_time'));

            $minMinutes = 8 * 60;
            $maxMinutes = 20 * 60;

            if ($startMinutes < $minMinutes || $endMinutes > $maxMinutes) {
                $validator->errors()->add('start_time', 'Time must be between 08:00 and 20:00.');
            }

            if ($startMinutes % 15 !== 0 || $endMinutes % 15 !== 0) {
                $validator->errors()->add('start_time', 'Time must be in 15-minute intervals.');
            }

            if ($request->input('class_type') !== 'online') {
                $roomId = $request->input('room_id');
                $setId = $request->input('set_id');

                if ($roomId && $setId) {
                    $room = Room::find($roomId);
                    $set = Set::find($setId);

                    if ($room && $set && $set->student_count > $room->capacity) {
                        $validator->errors()->add('room_id', 'Room capacity is not enough for the selected set.');
                    }
                }
            }
        });

        $data = $validator->validate();

        if ($data['class_type'] === 'online') {
            $data['room_id'] = null;
        } elseif (!$data['room_id']) {
            return $request->validate([
                'room_id' => ['required', 'exists:rooms,id'],
            ]) + $data;
        }

        $conflictQuery = Schedule::query()
            ->where('day', $data['day'])
            ->where('start_time', '<', $data['end_time'])
            ->where('end_time', '>', $data['start_time']);

        if ($current) {
            $conflictQuery->where('id', '!=', $current->id);
        }

        $roomConflict = null;
        if ($data['class_type'] !== 'online' && $data['room_id']) {
            $roomConflict = (clone $conflictQuery)->where('room_id', $data['room_id'])->exists();
            if ($roomConflict) {
                throw Validator::make([], [])->after(function ($validator) {
                    $validator->errors()->add('room_id', 'Room is already booked for this time.');
                })->validate();
            }
        }

        $facultyConflict = (clone $conflictQuery)->where('faculty_id', $data['faculty_id'])->exists();
        if ($facultyConflict) {
            throw Validator::make([], [])->after(function ($validator) {
                $validator->errors()->add('faculty_id', 'Faculty has a schedule conflict at this time.');
            })->validate();
        }

        $setConflict = (clone $conflictQuery)->where('set_id', $data['set_id'])->exists();
        if ($setConflict) {
            throw Validator::make([], [])->after(function ($validator) {
                $validator->errors()->add('set_id', 'Set already has a schedule at this time.');
            })->validate();
        }

        return $data;
    }

    private function minutesFromTime(string $time): int
    {
        [$hour, $minute] = explode(':', $time);
        return ((int) $hour) * 60 + (int) $minute;
    }
}

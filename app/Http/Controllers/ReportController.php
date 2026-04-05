<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use App\Models\Schedule;
use Illuminate\Support\Collection;

class ReportController extends Controller
{
    public function facultyLoad()
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $schedules = Schedule::with(['faculty', 'subject', 'set'])
            ->whereIn('day', $days)
            ->orderBy('faculty_id')
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $grouped = $schedules->groupBy('faculty_id');

        $rows = Faculty::query()
            ->orderBy('id')
            ->get()
            ->map(function ($faculty) use ($grouped, $days) {
                $facultySchedules = $grouped->get($faculty->id, collect());

                $totalMinutes = $facultySchedules->sum(fn ($schedule) => $this->minutesDiff($schedule->start_time, $schedule->end_time));

                $perDay = collect($days)->mapWithKeys(function ($day) use ($facultySchedules) {
                    $minutes = $facultySchedules
                        ->where('day', $day)
                        ->sum(fn ($schedule) => $this->minutesDiff($schedule->start_time, $schedule->end_time));
                    return [$day => $minutes];
                });

                $unitKeys = $facultySchedules
                    ->map(fn ($schedule) => $schedule->subject_id . ':' . $schedule->set_id)
                    ->unique()
                    ->values();

                $units = $unitKeys->sum(function ($key) use ($facultySchedules) {
                    $schedule = $facultySchedules->first(function ($item) use ($key) {
                        return $item->subject_id . ':' . $item->set_id === $key;
                    });
                    return $schedule?->subject?->units ?? 0;
                });

                return [
                    'faculty' => $faculty,
                    'totalMinutes' => $totalMinutes,
                    'totalHours' => $this->formatMinutes($totalMinutes),
                    'units' => $units,
                    'perDay' => $perDay->map(fn ($minutes) => $this->formatMinutes($minutes)),
                ];
            });

        return view('reports.faculty-load', [
            'days' => $days,
            'rows' => $rows,
        ]);
    }

    public function conflictReport()
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $schedules = Schedule::with(['subject', 'faculty', 'room', 'set'])
            ->whereIn('day', $days)
            ->orderBy('day')
            ->orderBy('start_time')
            ->get();

        $conflicts = collect();

        $byDay = $schedules->groupBy('day');
        foreach ($byDay as $day => $items) {
            $count = $items->count();
            for ($i = 0; $i < $count; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    $a = $items[$i];
                    $b = $items[$j];

                    if (!$this->overlaps($a->start_time, $a->end_time, $b->start_time, $b->end_time)) {
                        continue;
                    }

                    $overlapStart = max($a->start_time, $b->start_time);
                    $overlapEnd = min($a->end_time, $b->end_time);
                    $timeLabel = $this->formatTimeRange($overlapStart, $overlapEnd);

                    if ($a->room_id && $a->room_id === $b->room_id) {
                        $conflicts->push($this->buildConflict('Room', $day, $timeLabel, $a->room?->building_name . ' ' . $a->room?->room_name, $a, $b));
                    }

                    if ($a->faculty_id === $b->faculty_id) {
                        $conflicts->push($this->buildConflict('Faculty', $day, $timeLabel, $a->faculty?->name, $a, $b));
                    }

                    if ($a->set_id === $b->set_id) {
                        $conflicts->push($this->buildConflict('Set', $day, $timeLabel, $a->set?->display_name, $a, $b));
                    }
                }
            }
        }

        return view('reports.conflicts', [
            'conflicts' => $conflicts,
        ]);
    }

    public function roomUtilization()
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $totalMinutesPerDay = 12 * 60; // 08:00 - 20:00

        $schedules = Schedule::with(['room'])
            ->whereIn('day', $days)
            ->whereNotNull('room_id')
            ->orderBy('room_id')
            ->get();

        $rooms = $schedules->groupBy('room_id');

        $rows = $rooms->map(function (Collection $roomSchedules) use ($days, $totalMinutesPerDay) {
            $room = $roomSchedules->first()->room;
            $perDay = collect($days)->mapWithKeys(function ($day) use ($roomSchedules, $totalMinutesPerDay) {
                $minutes = $roomSchedules
                    ->where('day', $day)
                    ->sum(fn ($schedule) => $this->minutesDiff($schedule->start_time, $schedule->end_time));
                $percent = $totalMinutesPerDay > 0 ? round(($minutes / $totalMinutesPerDay) * 100) : 0;
                return [$day => $percent];
            });

            $totalMinutes = $roomSchedules->sum(fn ($schedule) => $this->minutesDiff($schedule->start_time, $schedule->end_time));
            $totalPossible = $totalMinutesPerDay * count($days);
            $weeklyPercent = $totalPossible > 0 ? round(($totalMinutes / $totalPossible) * 100) : 0;

            return [
                'room' => $room,
                'perDay' => $perDay,
                'weeklyPercent' => $weeklyPercent,
            ];
        })->values();

        return view('reports.room-utilization', [
            'days' => $days,
            'rows' => $rows,
        ]);
    }

    public function batchExport()
    {
        return view('reports.batch-export');
    }

    private function minutesDiff(string $start, string $end): int
    {
        [$sh, $sm] = explode(':', $start);
        [$eh, $em] = explode(':', $end);
        return ((int) $eh * 60 + (int) $em) - ((int) $sh * 60 + (int) $sm);
    }

    private function formatMinutes(int $minutes): string
    {
        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        if ($hours === 0 && $mins === 0) {
            return '0 hrs';
        }

        if ($mins === 0) {
            return sprintf('%d hrs', $hours);
        }

        return sprintf('%d hrs %d mins', $hours, $mins);
    }

    private function overlaps(string $startA, string $endA, string $startB, string $endB): bool
    {
        return $startA < $endB && $endA > $startB;
    }

    private function formatTimeRange(string $start, string $end): string
    {
        return sprintf(
            '%s - %s',
            \Carbon\Carbon::parse($start)->format('g:i A'),
            \Carbon\Carbon::parse($end)->format('g:i A')
        );
    }

    private function buildConflict(string $type, string $day, string $timeLabel, ?string $entity, $a, $b): array
    {
        return [
            'type' => $type,
            'day' => $day,
            'time' => $timeLabel,
            'entity' => $entity ?? 'N/A',
            'a' => $a,
            'b' => $b,
        ];
    }
}

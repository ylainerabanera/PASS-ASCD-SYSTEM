<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page { size: 13in 8.5in; margin: 8mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 8px; color: #0b1220; }
        h2 { margin: 0 0 2px 0; font-size: 13px; text-align: center; }
        .subtitle { text-align: center; margin-bottom: 6px; color: #334155; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #4b5563; padding: 1px 2px; text-align: center; vertical-align: middle; height: 10px; line-height: 1.05; }
        th { background: #1f4e79; color: #fff; font-weight: 600; }
        .time-col { width: 85px; background: #eef2f7; color: #0b1220; font-weight: 600; white-space: nowrap; }
        thead .time-col { background: #1f4e79; color: #fff; }
        .filled { background: #e9f4c8; white-space: normal; word-wrap: break-word; word-break: break-word; overflow-wrap: anywhere; }
        .filled div { white-space: normal; word-wrap: break-word; word-break: break-word; overflow-wrap: anywhere; }
        .muted { color: #475569; font-size: 7px; line-height: 1.1; white-space: normal; word-wrap: break-word; word-break: break-word; overflow-wrap: anywhere; }
        .page-break { page-break-before: always; }
        .summary-title { margin: 0 0 6px 0; font-size: 12px; text-align: left; }
        .summary-table { width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 9px; }
        .summary-table th,
        .summary-table td { border: 1px solid #2f3b4b; padding: 4px 5px; vertical-align: middle; text-align: left; height: auto; line-height: 1.25; word-break: break-word; overflow-wrap: anywhere; }
        .summary-table th { background: #eef2f7; color: #0b1220; text-align: center; }
        .summary-table .text-center { text-align: center; }
        .summary-table .schedule-cell { white-space: normal; text-align: left; }
        .summary-table .schedule-piece { margin-bottom: 2px; }
        .summary-table .schedule-piece:last-child { margin-bottom: 0; }
        .summary-table .total-row td { font-weight: 700; background: #f8fafc; }
    </style>
</head>
<body>
    @php
        $summaryRows = $schedules
            ->groupBy(fn ($schedule) => $schedule->subject_id . ':' . $schedule->set_id)
            ->map(function ($items) {
                $items = $items->sortBy([
                    ['day', 'asc'],
                    ['start_time', 'asc'],
                ])->values();

                $first = $items->first();
                $set = $first->set;
                $subject = $first->subject;

                $courseYear = $set->course->name . ' ' . $set->year_level;
                if ($set->set_code) {
                    $courseYear .= ' - ' . $set->set_code;
                }

                $scheduleParts = $items->map(function ($schedule) {
                    $time = \Carbon\Carbon::parse($schedule->start_time)->format('g:ia') . ' - ' . \Carbon\Carbon::parse($schedule->end_time)->format('g:ia');
                    $modeOrRoom = $schedule->class_type === 'online'
                        ? 'Online'
                        : trim(($schedule->room?->building_name ?? '') . ' ' . ($schedule->room?->room_name ?? ''));

                    return $schedule->day . ' | ' . strtoupper($time) . ' | ' . $modeOrRoom;
                })->values();

                $modes = $items->pluck('class_type')->unique()->values();
                $mode = $modes->count() > 1
                    ? 'HYBRID'
                    : ($modes->first() === 'online' ? 'Online' : 'F2F');

                return [
                    'subject_code' => $subject->subject_code,
                    'subject_name' => $subject->subject_name,
                    'course_year' => $courseYear,
                    'schedule_parts' => $scheduleParts,
                    'mode' => $mode,
                    'units' => $subject->units,
                ];
            })
            ->values();

        $totalUnits = $summaryRows->sum('units');
    @endphp

    <h2>{{ $title }}</h2>
    @isset($subtitle)
        <div class="subtitle">{{ $subtitle }}</div>
    @endisset
    <table>
        <thead>
            <tr>
                <th class="time-col">Time</th>
                @foreach ($days as $day)
                    <th>{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($slots as $index => $slot)
                <tr>
                    <td class="time-col">{{ $slot['label'] }}</td>
                    @foreach ($days as $day)
                        @php $cell = $grid[$day][$index]; @endphp
                        @if ($cell['type'] === 'skip')
                            @continue
                        @elseif ($cell['type'] === 'filled')
                            @php $schedule = $cell['schedule']; @endphp
                            <td rowspan="{{ $cell['rowspan'] }}" class="filled">
                                <div><strong>{{ $schedule->subject->subject_code }}</strong></div>
                                <div class="muted">{{ $schedule->subject->subject_name }}</div>
                                <div class="muted">{{ $schedule->set->display_name }}</div>
                                <div class="muted">{{ $schedule->faculty->name }}</div>
                                <div class="muted">{{ $schedule->room ? $schedule->room->building_name . ' ' . $schedule->room->room_name : 'Online' }}</div>
                            </td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <h2>{{ $title }}</h2>
    @isset($subtitle)
        <div class="subtitle">{{ $subtitle }}</div>
    @endisset
    <div class="summary-title"><strong>Summary</strong></div>
    <table class="summary-table">
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 12%;">Subject Code</th>
                <th style="width: 30%;">Subject</th>
                <th style="width: 12%;">Course &amp; Year</th>
                <th style="width: 30%;">Schedule</th>
                <th style="width: 6%;">Mode</th>
                <th style="width: 5%;">Units</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($summaryRows as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $row['subject_code'] }}</td>
                    <td>{{ $row['subject_name'] }}</td>
                    <td class="text-center">{{ $row['course_year'] }}</td>
                    <td class="schedule-cell">
                        @foreach ($row['schedule_parts'] as $part)
                            <div class="schedule-piece">{{ $part }}</div>
                        @endforeach
                    </td>
                    <td class="text-center">{{ $row['mode'] }}</td>
                    <td class="text-center">{{ $row['units'] }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="6" class="text-center">Total Units</td>
                <td class="text-center">{{ $totalUnits }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>

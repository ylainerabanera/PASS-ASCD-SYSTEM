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
        th, td { border: 1px solid #4b5563; padding: 1px 2px; text-align: center; vertical-align: middle; height: 10px; line-height: 1.05; white-space: nowrap; }
        th { background: #1f4e79; color: #fff; font-weight: 600; }
        .time-col { width: 85px; background: #eef2f7; color: #0b1220; font-weight: 600; }
        thead .time-col { background: #1f4e79; color: #fff; }
        .filled { background: #e9f4c8; }
        .muted { color: #475569; font-size: 7px; line-height: 1.1; }
    </style>
</head>
<body>
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
</body>
</html>

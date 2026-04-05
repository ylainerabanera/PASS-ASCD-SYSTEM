<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #f1f1f1; }
    </style>
</head>
<body>
    <h2>{{ $title }}</h2>
    <table>
        <thead>
            <tr>
                <th>Day</th>
                <th>Time</th>
                <th>Subject</th>
                <th>Set</th>
                <th>Faculty</th>
                <th>Room</th>
                <th>Class Type</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($schedules as $schedule)
                <tr>
                    <td>{{ $schedule->day }}</td>
                    <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                    <td>{{ $schedule->subject->subject_code }} - {{ $schedule->subject->subject_name }}</td>
                    <td>{{ $schedule->set->display_name }}</td>
                    <td>{{ $schedule->faculty->name }}</td>
                    <td>{{ $schedule->room ? $schedule->room->building_name . ' ' . $schedule->room->room_name : 'Online' }}</td>
                    <td>{{ $schedule->class_type === 'online' ? 'Online' : 'Face-to-Face' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No schedules available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

<table class="table align-middle">
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
                <td>{{ $schedule->class_type === 'online' ? '—' : ($schedule->room ? $schedule->room->building_name . ' ' . $schedule->room->room_name : '—') }}</td>
                <td>{{ $schedule->class_type === 'online' ? 'Online' : 'Face-to-Face' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="7">No schedules available.</td>
            </tr>
        @endforelse
    </tbody>
</table>

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Room Utilization</h3>
            <div class="text-muted small">Percentage of room usage per day and overall weekly utilization.</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Room</th>
                        @foreach ($days as $day)
                            <th>{{ $day }}</th>
                        @endforeach
                        <th>Weekly %</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rows as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row['room']->building_name }} {{ $row['room']->room_name }}</td>
                            @foreach ($days as $day)
                                <td>{{ $row['perDay'][$day] }}%</td>
                            @endforeach
                            <td>{{ $row['weeklyPercent'] }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 2 + count($days) + 1 }}" class="text-muted">No utilization data yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

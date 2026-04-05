@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Conflict Report</h3>
            <div class="text-muted small">Room, faculty, and set overlaps by day and time.</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Type</th>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Entity</th>
                        <th>Schedules</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($conflicts as $conflict)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $conflict['type'] }}</td>
                            <td>{{ $conflict['day'] }}</td>
                            <td>{{ $conflict['time'] }}</td>
                            <td>{{ $conflict['entity'] }}</td>
                            <td>
                                <div class="small">
                                    {{ $conflict['a']->subject->subject_code }} ({{ $conflict['a']->faculty->name }}) · {{ $conflict['a']->set->display_name }}
                                </div>
                                <div class="small text-muted">
                                    {{ $conflict['b']->subject->subject_code }} ({{ $conflict['b']->faculty->name }}) · {{ $conflict['b']->set->display_name }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted">No conflicts detected.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

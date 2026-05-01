@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Schedules</h3>
            <a class="btn btn-add" href="{{ route('schedules.create') }}"><i class="bi bi-plus-lg me-1"></i>Add Schedule</a>
        </div>

        @include('partials.flash')

        @php
            $groupedSchedules = $schedules->groupBy('day');
            $orderedDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        @endphp

        @forelse ($orderedDays as $day)
            @php $daySchedules = $groupedSchedules->get($day, collect()); @endphp
            <div class="card mb-3">
                <div class="card-header">{{ $day }}</div>
                <div class="card-body">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Time</th>
                                <th>Subject</th>
                                <th>Set</th>
                                <th>Faculty</th>
                                <th>Room</th>
                                <th>Class Type</th>
                                <th>G Code</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($daySchedules as $schedule)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }} -
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}</td>
                                    <td>{{ $schedule->subject->subject_code }} - {{ $schedule->subject->subject_name }}</td>
                                    <td>{{ $schedule->set->display_name }}</td>
                                    <td>{{ $schedule->faculty->name }}</td>
                                    <td>
                                        {{ $schedule->class_type === 'online' ? '—' : ($schedule->room ? $schedule->room->building_name . ' ' . $schedule->room->room_name : '—') }}
                                    </td>
                                    <td>{{ $schedule->class_type === 'online' ? 'Online' : 'Face-to-Face' }}</td>
                                    <td>
                                        @if ($schedule->class_type === 'online')
                                            @php $value = $schedule->g_code ?? 'none'; @endphp

                                            @if ($value === 'none')
                                                <i style="color:#797979">none</i>
                                            @else
                                                {{ $value }}
                                            @endif
                                        @else
                                            <i style="color:#797979">onsite</i>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a class="btn btn-sm btn-edit"
                                                href="{{ route('schedules.edit', $schedule) }}"><i
                                                    class="bi bi-pencil-square me-1"></i>Edit</a>
                                            <form action="{{ route('schedules.destroy', $schedule) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-delete"
                                                    onclick="return confirm('Delete this schedule?')"><i
                                                        class="bi bi-trash me-1"></i>Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-muted">No schedules for {{ $day }}.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-muted">No schedules yet.</div>
            </div>
        @endforelse
    </div>
@endsection

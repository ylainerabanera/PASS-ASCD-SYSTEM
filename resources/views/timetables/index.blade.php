@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Timetables</h3>
        <a class="btn btn-outline-primary" href="{{ route('timetables.online') }}">Online Classes ({{ $onlineCount }})</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Faculty Timetables</div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse ($faculties as $faculty)
                            <a class="list-group-item list-group-item-action" href="{{ route('timetables.faculty', $faculty) }}">
                                {{ $faculty->name }}
                            </a>
                        @empty
                            <div class="text-muted">No faculties yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Room Timetables</div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse ($rooms as $room)
                            <a class="list-group-item list-group-item-action" href="{{ route('timetables.room', $room) }}">
                                {{ $room->building_name }} {{ $room->room_name }}
                            </a>
                        @empty
                            <div class="text-muted">No rooms yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Course Timetables</div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse ($courses as $course)
                            <a class="list-group-item list-group-item-action" href="{{ route('timetables.course', $course) }}">
                                {{ $course->name }}
                            </a>
                        @empty
                            <div class="text-muted">No courses yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Set Timetables</div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse ($sets as $set)
                            <a class="list-group-item list-group-item-action" href="{{ route('timetables.set', $set) }}">
                                {{ $set->display_name }}
                            </a>
                        @empty
                            <div class="text-muted">No sets yet.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

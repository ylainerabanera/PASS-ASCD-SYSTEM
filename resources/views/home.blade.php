@extends('layouts.app')

@section('content')
@php
    $stats = $__data['stats'] ?? [];
    $facultyCount = $stats['facultyCount'] ?? 0;
    $courseCount = $stats['courseCount'] ?? 0;
    $scheduleCount = $stats['scheduleCount'] ?? 0;
    $roomCount = $stats['roomCount'] ?? 0;
@endphp

<div class="dashboard">
    <div class="dashboard-header">
        <div>
            <h2>Dashboard</h2>
            <p class="text-muted">Welcome back, PASS Admin. Here’s what’s happening today.</p>
        </div>
    </div>

    <div class="stats-grid">
        @include('partials.dashboard.stat-card', ['icon' => 'bi bi-people', 'number' => $facultyCount, 'label' => 'Faculties'])
        @include('partials.dashboard.stat-card', ['icon' => 'bi bi-journal-bookmark', 'number' => $courseCount, 'label' => 'Courses'])
        @include('partials.dashboard.stat-card', ['icon' => 'bi bi-calendar2-week', 'number' => $scheduleCount, 'label' => 'Schedules'])
        @include('partials.dashboard.stat-card', ['icon' => 'bi bi-door-closed', 'number' => $roomCount, 'label' => 'Rooms'])
    </div>

    <div class="dashboard-grid">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0">Ongoing Classes Today</h5>
                    <span class="badge bg-light text-dark">{{ $todayName }}</span>
                </div>
                <div class="ongoing-list">
                    @forelse ($ongoing as $item)
                        <div class="ongoing-item">
                            <div class="ongoing-time">
                                {{ \Carbon\Carbon::parse($item->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($item->end_time)->format('g:i A') }}
                            </div>
                            <div class="ongoing-meta">
                                <div class="fw-semibold">{{ $item->subject->subject_code }} - {{ $item->subject->subject_name }}</div>
                                <div class="text-muted small">
                                    {{ $item->faculty->name }}
                                </div>
                                <div class="text-muted small">
                                    {{ $item->set->display_name }} ·
                                    {{ $item->class_type === 'online' ? 'Online' : ($item->room ? $item->room->building_name . '    ' . $item->room->room_name : '') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted">No classes scheduled today.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="calendar-header">
                    <div class="fw-semibold">{{ $today->format('F Y') }}</div>
                    <div class="calendar-today">Today: {{ $today->format('M d') }}</div>
                </div>
                <div class="calendar-grid">
                    <div class="calendar-label">Mon</div>
                    <div class="calendar-label">Tue</div>
                    <div class="calendar-label">Wed</div>
                    <div class="calendar-label">Thu</div>
                    <div class="calendar-label">Fri</div>
                    <div class="calendar-label">Sat</div>
                    <div class="calendar-label">Sun</div>

                    @for ($i = 1; $i < $startWeekday; $i++)
                        <div class="calendar-cell muted"></div>
                    @endfor

                    @for ($day = 1; $day <= $daysInMonth; $day++)
                        @php $isToday = $day === (int) $today->format('j'); @endphp
                        <div class="calendar-cell {{ $isToday ? 'today' : '' }}">{{ $day }}</div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Scheduling Overview</h5>
            <div class="overview-grid">
                @include('partials.dashboard.overview-row', ['label' => 'Faculties', 'value' => $facultyCount, 'multiplier' => 8])
                @include('partials.dashboard.overview-row', ['label' => 'Courses', 'value' => $courseCount, 'multiplier' => 6])
                @include('partials.dashboard.overview-row', ['label' => 'Schedules', 'value' => $scheduleCount, 'multiplier' => 4])
                @include('partials.dashboard.overview-row', ['label' => 'Rooms', 'value' => $roomCount, 'multiplier' => 10])
            </div>
        </div>
    </div>
</div>
@endsection

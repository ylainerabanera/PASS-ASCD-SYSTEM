@extends('layouts.app')

@section('content')
@php
    $today = \Carbon\Carbon::now('Asia/Manila');
    $todayName = $today->format('l');
    $ongoing = \App\Models\Schedule::with(['subject', 'faculty', 'room', 'set'])
        ->where('day', $todayName)
        ->orderBy('start_time')
        ->get();

    $facultyCount = \App\Models\Faculty::count();
    $courseCount = \App\Models\Course::count();
    $scheduleCount = \App\Models\Schedule::count();
    $roomCount = \App\Models\Room::count();

    $monthStart = $today->copy()->startOfMonth();
    $daysInMonth = $today->daysInMonth;
    $startWeekday = (int) $monthStart->dayOfWeekIso; // 1 (Mon) - 7 (Sun)
@endphp

<div class="dashboard">
    <div class="dashboard-header">
        <div>
            <h2>Dashboard</h2>
            <p class="text-muted">Welcome back, Admin. Here’s what’s happening today.</p>
        </div>
    </div>

    @include('partials.flash')

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-people"></i></div>
            <div>
                <div class="stat-number">{{ $facultyCount }}</div>
                <div class="stat-label">Faculties</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-journal-bookmark"></i></div>
            <div>
                <div class="stat-number">{{ $courseCount }}</div>
                <div class="stat-label">Courses</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-calendar2-week"></i></div>
            <div>
                <div class="stat-number">{{ $scheduleCount }}</div>
                <div class="stat-label">Schedules</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-door-closed"></i></div>
            <div>
                <div class="stat-number">{{ $roomCount }}</div>
                <div class="stat-label">Rooms</div>
            </div>
        </div>
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
                <div class="overview-row">
                    <span>Faculties</span>
                    <div class="overview-bar"><span style="width: {{ min(100, $facultyCount * 8) }}%"></span></div>
                </div>
                <div class="overview-row">
                    <span>Courses</span>
                    <div class="overview-bar"><span style="width: {{ min(100, $courseCount * 6) }}%"></span></div>
                </div>
                <div class="overview-row">
                    <span>Schedules</span>
                    <div class="overview-bar"><span style="width: {{ min(100, $scheduleCount * 4) }}%"></span></div>
                </div>
                <div class="overview-row">
                    <span>Rooms</span>
                    <div class="overview-bar"><span style="width: {{ min(100, $roomCount * 10) }}%"></span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-mark">
            <img src="/images/logo.png" alt="Logo">
        </div>
        <div class="brand-text">
            <div class="brand-title">PASS COLLEGE</div>
            <div class="brand-sub">ACSD SYSTEM</div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2"></i><span>Dashboard</span></a>
        <a class="sidebar-link {{ request()->routeIs('faculties.*') ? 'active' : '' }}" href="{{ route('faculties.index') }}"><i class="bi bi-people"></i><span>Faculties</span></a>
        <a class="sidebar-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" href="{{ route('courses.index') }}"><i class="bi bi-journal-bookmark"></i><span>Courses</span></a>
        <a class="sidebar-link {{ request()->routeIs('schedules.*') ? 'active' : '' }}" href="{{ route('schedules.index') }}"><i class="bi bi-calendar2-week"></i><span>Schedules</span></a>
        <a class="sidebar-link {{ request()->routeIs('rooms.*') ? 'active' : '' }}" href="{{ route('rooms.index') }}"><i class="bi bi-door-closed"></i><span>Rooms</span></a>
        {{-- <a class="sidebar-link {{ request()->routeIs('room-reservations.*') ? 'active' : '' }}" href="{{ route('room-reservations.index') }}"><i class="bi bi-calendar-check"></i><span>Room Reservations</span></a> --}}
        <a class="sidebar-link {{ request()->routeIs('sets.*') ? 'active' : '' }}" href="{{ route('sets.index') }}"><i class="bi bi-grid"></i><span>Sets</span></a>
        <a class="sidebar-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}" href="{{ route('subjects.index') }}"><i class="bi bi-book"></i><span>Subjects</span></a>
        <a class="sidebar-link {{ request()->routeIs('timetables.*') ? 'active' : '' }}" href="{{ route('timetables.index') }}"><i class="bi bi-table"></i><span>Timetables</span></a>
        <a class="sidebar-link {{ request()->routeIs('reports.batch-export') ? 'active' : '' }}" href="{{ route('reports.batch-export') }}"><i class="bi bi-download"></i><span>Batch Export</span></a>
        <a class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i class="bi bi-person-circle"></i><span>Users</span></a>
    </nav>
</aside>

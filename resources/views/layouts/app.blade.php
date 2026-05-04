<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>PASS - ASCD System</title>
    <link rel="icon" type="image/png" href="/images/Logo.png">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <script>
        (function () {
            try {
                if (window.innerWidth > 992 && localStorage.getItem('sidebar-collapsed') === '1') {
                    document.documentElement.classList.add('sidebar-collapsed');
                }
            } catch (e) {}
        })();
    </script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
</head>
<body>
    <div id="app" class="app-shell">
        @auth
            <div class="app-layout">
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
                        <a class="sidebar-link {{ request()->routeIs('sets.*') ? 'active' : '' }}" href="{{ route('sets.index') }}"><i class="bi bi-grid"></i><span>Sets</span></a>
                        <a class="sidebar-link {{ request()->routeIs('subjects.*') ? 'active' : '' }}" href="{{ route('subjects.index') }}"><i class="bi bi-book"></i><span>Subjects</span></a>
                        <a class="sidebar-link {{ request()->routeIs('timetables.*') ? 'active' : '' }}" href="{{ route('timetables.index') }}"><i class="bi bi-table"></i><span>Timetables</span></a>
                        <button class="sidebar-link report-toggle {{ request()->routeIs('reports.*') ? 'active' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#reportMenu" aria-expanded="{{ request()->routeIs('reports.*') ? 'true' : 'false' }}" aria-controls="reportMenu">
                            <i class="bi bi-bar-chart"></i><span>Reports</span>
                            <i class="bi bi-caret-down-fill ms-auto"></i>
                        </button>
                        <div class="collapse report-menu {{ request()->routeIs('reports.*') ? 'show' : '' }}" id="reportMenu">
                            {{-- <a class="sidebar-link report-item {{ request()->routeIs('reports.faculty-load') ? 'active' : '' }}" href="{{ route('reports.faculty-load') }}">Faculty Load</a> --}}
                            {{-- <a class="sidebar-link report-item {{ request()->routeIs('reports.conflicts') ? 'active' : '' }}" href="{{ route('reports.conflicts') }}">Conflict Report</a> --}}
                            <a class="sidebar-link report-item {{ request()->routeIs('reports.faculty-availability') ? 'active' : '' }}" href="{{ route('reports.faculty-availability') }}">Faculty Availability</a>
                            {{-- <a class="sidebar-link report-item {{ request()->routeIs('reports.room-utilization') ? 'active' : '' }}" href="{{ route('reports.room-utilization') }}">Room Utilization</a> --}}
                            <a class="sidebar-link report-item {{ request()->routeIs('reports.batch-export') ? 'active' : '' }}" href="{{ route('reports.batch-export') }}">Batch Export</a>
                        </div>
                        <a class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><i class="bi bi-person-circle"></i><span>Users</span></a>
                    </nav>
                </aside>
                <div class="main-content">
                    <header class="topbar">
                        <div class="topbar-title">
                            <button class="btn btn-icon" type="button" data-sidebar-toggle aria-label="Toggle sidebar">
                                <i class="bi bi-list"></i>
                            </button>
                            <div class="topbar-search">
                                <i class="bi bi-search"></i>
                                <input type="text" placeholder="Search..." aria-label="Search" data-global-search>
                            </div>
                        </div>
                        <div class="topbar-actions">
                            <div class="topbar-user dropdown">
                                <a class="user-chip dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle"></i>
                                    <span>{{ Auth::user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </header>
                    <main class="content-area">
                        @yield('content')
                    </main>
                    <footer class="app-footer">
                        &copy; {{ date('Y') }} PASS - ASCD System. All rights reserved.
                    </footer>
                </div>
            </div>
        @else
            <main class="auth-content">
                @yield('content')
            </main>
        @endauth
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
</html>

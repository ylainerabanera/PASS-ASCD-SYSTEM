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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script>
        window.SweetAlertMessages = {
            success: @json(session('status')),
            error: @json(session('error')),
            validation: @json($errors->all()),
            redirect: @json(session('redirect_to')),
        };
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
</head>
<body>
    <div id="app" class="app-shell">
        @auth
            <div class="app-layout">
                @include('partials.sidebar-nav')
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
                        <div class="topbar-actions d-flex align-items-center gap-2">
                            <a href="{{ route('room-reservations.index') }}" class="btn btn-icon position-relative" title="Room Reservations">
                                <i class="bi bi-calendar-check"></i>
                                @if (!empty($roomReservationCount) && $roomReservationCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $roomReservationCount }}
                                        <span class="visually-hidden">room reservations</span>
                                    </span>
                                @endif
                            </a>
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

@extends('layouts.app')

@section('content')
<div class="login-screen">
    <div class="login-hero"></div>
    <nav class="login-topbar">
        <div class="login-topbar-logo">
            <img src="/images/logo.png" alt="Logo">
        </div>
        <div class="login-topbar-text">PASS COLLEGE</div>
    </nav>
    <div class="login-card">
        <div class="login-logo">
            <img src="/images/logo.png" alt="Logo">
        </div>
        <h2>Academic Scheduling with<br>Conflict Detection</h2>
        <div class="login-subtitle">Login</div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3 text-start">
                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                <input id="email" type="email" class="form-control login-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Enter Email Address" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="mb-3 text-start">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control login-input @error('password') is-invalid @enderror" name="password" placeholder="Enter Password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="small login-link" href="{{ route('password.request') }}">{{ __('Forgot Password?') }}</a>
                @endif
            </div>

            <button type="submit" class="btn btn-login w-100">Login</button>
        </form>
        {{-- <div class="login-footer">Don't have an account? <span>Register</span></div> --}}
    </div>
</div>
@endsection

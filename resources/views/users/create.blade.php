@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Add Admin User</h3>

    @include('partials.errors')

    <form method="POST" action="{{ route('users.store') }}" class="card card-body">
        @csrf
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button class="btn btn-primary">Save</button>
        <a class="cancel-link" href="{{ route('users.index') }}">Cancel</a>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Users</h3>
        @if ($users->count() === 0)
            <a class="btn btn-add" href="{{ route('users.create') }}"><i class="bi bi-plus-lg me-1"></i>Add Admin</a>
        @endif
    </div>

    @include('partials.flash')

    <div class="card">
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->is_admin ? 'Admin' : 'User' }}</td>
                            <td>
                                <a class="btn btn-sm btn-edit" href="{{ route('users.edit', $user) }}"><i class="bi bi-pencil-square me-1"></i>Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No users yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

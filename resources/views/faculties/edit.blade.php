@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Edit Faculty</h3>

    @include('partials.errors')

    <form method="POST" action="{{ route('faculties.update', $faculty) }}" class="card card-body">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $faculty->name) }}" required>
        </div>
        <button class="btn btn-primary">Update</button>
        <a class="cancel-link mt-3 text-center" href="{{ route('faculties.index') }}">Cancel</a>
    </form>
</div>
@endsection

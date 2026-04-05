@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Add Course</h3>

    @include('partials.errors')

    <form method="POST" action="{{ route('courses.store') }}" class="card card-body">
        @csrf
        <div class="mb-3">
            <label class="form-label">Course Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <button class="btn btn-primary">Save</button>
        <a class="cancel-link mt-3 text-center" href="{{ route('courses.index') }}">Cancel</a>
    </form>
</div>
@endsection

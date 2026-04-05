@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Add Subject</h3>

    @include('partials.errors')

    <form method="POST" action="{{ route('subjects.store') }}" class="card card-body">
        @csrf
        <div class="mb-3">
            <label class="form-label">Subject Code</label>
            <input type="text" name="subject_code" class="form-control" value="{{ old('subject_code') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Subject Name</label>
            <input type="text" name="subject_name" class="form-control" value="{{ old('subject_name') }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Units</label>
            <input type="number" name="units" class="form-control" value="{{ old('units', 3) }}" min="1" max="9" required>
        </div>
        <button class="btn btn-primary">Save</button>
        <a class="cancel-link mt-3 text-center" href="{{ route('subjects.index') }}">Cancel</a>
    </form>
</div>
@endsection

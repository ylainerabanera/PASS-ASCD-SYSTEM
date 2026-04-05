@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Add Set</h3>

    @include('partials.errors')

    <form method="POST" action="{{ route('sets.store') }}" class="card card-body">
        @csrf
        <div class="mb-3">
            <label class="form-label">Course</label>
            <select name="course_id" class="form-select" required>
                <option value="">Select course</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Year Level</label>
            <select name="year_level" class="form-select" required>
                @for ($i = 1; $i <= 4; $i++)
                    <option value="{{ $i }}" @selected(old('year_level') == $i)>{{ $i }}{{ $i === 1 ? 'st' : ($i === 2 ? 'nd' : ($i === 3 ? 'rd' : 'th')) }} Year</option>
                @endfor
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Set (A-F or leave blank if none)</label>
            <input type="text" name="set_code" class="form-control" value="{{ old('set_code') }}" maxlength="1">
        </div>
        <div class="mb-3">
            <label class="form-label">Student Count</label>
            <input type="number" name="student_count" class="form-control" value="{{ old('student_count', 0) }}" min="0" required>
        </div>
        <button class="btn btn-primary">Save</button>
        <a class="cancel-link mt-3 text-center" href="{{ route('sets.index') }}">Cancel</a>
    </form>
</div>
@endsection

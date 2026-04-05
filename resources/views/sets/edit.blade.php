@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Edit Set</h3>

    @include('partials.errors')

    <form method="POST" action="{{ route('sets.update', $set) }}" class="card card-body">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Course</label>
            <select name="course_id" class="form-select" required>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id', $set->course_id) == $course->id)>{{ $course->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Year Level</label>
            <select name="year_level" class="form-select" required>
                @for ($i = 1; $i <= 4; $i++)
                    <option value="{{ $i }}" @selected(old('year_level', $set->year_level) == $i)>{{ $i }}{{ $i === 1 ? 'st' : ($i === 2 ? 'nd' : ($i === 3 ? 'rd' : 'th')) }} Year</option>
                @endfor
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Set (A-F or leave blank if none)</label>
            <input type="text" name="set_code" class="form-control" value="{{ old('set_code', $set->set_code) }}" maxlength="1">
        </div>
        <div class="mb-3">
            <label class="form-label">Student Count</label>
            <input type="number" name="student_count" class="form-control" value="{{ old('student_count', $set->student_count) }}" min="0" required>
        </div>
        <button class="btn btn-primary">Update</button>
        <a class="cancel-link mt-3 text-center" href="{{ route('sets.index') }}">Cancel</a>
    </form>
</div>
@endsection

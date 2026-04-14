@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Courses</h3>
        <a class="btn btn-add" href="{{ route('courses.create') }}"><i class="bi bi-plus-lg me-1"></i>Add Course</a>
    </div>

    @include('partials.flash')

    <div class="card">
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $course->name }}</td>
                            <td>
                                <a class="btn btn-sm btn-edit" href="{{ route('courses.edit', $course) }}"><i class="bi bi-pencil-square me-1"></i>Edit</a>
                                {{-- <a class="btn btn-sm btn-view" href="{{ route('timetables.index') }}"><i class="bi bi-calendar3 me-1"></i>Timetable</a> --}}
                                <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-delete" onclick="return confirm('Delete this course?')"><i class="bi bi-trash me-1"></i>Delete</button>
                                </form>
                                <a class="btn btn-sm btn-download" href="{{ route('exports.course.pdf', $course) }}" target="_blank" rel="noopener"><i class="bi bi-file-earmark-pdf me-1"></i>PDF</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No courses yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

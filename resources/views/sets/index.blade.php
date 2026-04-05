@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Sets</h3>
        <a class="btn btn-add" href="{{ route('sets.create') }}"><i class="bi bi-plus-lg me-1"></i>Add Set</a>
    </div>

    @include('partials.flash')

    <div class="card">
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Course</th>
                        <th>Year Level</th>
                        <th>Set</th>
                        <th>Students</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sets as $set)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $set->course->name }}</td>
                            <td>{{ $set->year_level }}{{ $set->year_level === 1 ? 'st' : ($set->year_level === 2 ? 'nd' : ($set->year_level === 3 ? 'rd' : 'th')) }} Year</td>
                            <td>{{ $set->set_code ?? 'No Set' }}</td>
                            <td>{{ $set->student_count }}</td>
                            <td>
                                <div class="table-actions">
                                    <a class="btn btn-sm btn-edit" href="{{ route('sets.edit', $set) }}"><i class="bi bi-pencil-square me-1"></i>Edit</a>
                                    <a class="btn btn-sm btn-view" href="{{ route('timetables.set', $set) }}"><i class="bi bi-calendar3 me-1"></i>Timetable</a>
                                    <form action="{{ route('sets.destroy', $set) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-delete" onclick="return confirm('Delete this set?')"><i class="bi bi-trash me-1"></i>Delete</button>
                                    </form>
                                    <a class="btn btn-sm btn-download" href="{{ route('exports.set.pdf', $set) }}" target="_blank" rel="noopener"><i class="bi bi-file-earmark-pdf me-1"></i>PDF</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">No sets yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

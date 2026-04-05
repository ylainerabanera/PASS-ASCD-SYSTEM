@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Subjects</h3>
        <a class="btn btn-add" href="{{ route('subjects.create') }}"><i class="bi bi-plus-lg me-1"></i>Add Subject</a>
    </div>

    @include('partials.flash')

    <div class="card">
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Units</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($subjects as $subject)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $subject->subject_code }}</td>
                            <td>{{ $subject->subject_name }}</td>
                            <td>{{ $subject->units }}</td>
                            <td>
                                <a class="btn btn-sm btn-edit" href="{{ route('subjects.edit', $subject) }}"><i class="bi bi-pencil-square me-1"></i>Edit</a>
                                <form action="{{ route('subjects.destroy', $subject) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-delete" onclick="return confirm('Delete this subject?')"><i class="bi bi-trash me-1"></i>Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No subjects yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

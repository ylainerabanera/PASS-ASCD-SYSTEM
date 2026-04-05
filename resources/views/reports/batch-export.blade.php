@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Batch Export</h3>
            {{-- <div class="text-muted small">Download all schedules by faculty, course, or room in one click.</div> --}}
            <div class="text-muted small">Download all schedules by faculty, course, or room.</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body d-flex flex-wrap gap-2">
            <a class="btn btn-download" href="{{ route('exports.batch.faculty') }}"><i class="bi bi-download me-1"></i>Export All Faculty</a>
            <a class="btn btn-download" href="{{ route('exports.batch.course') }}"><i class="bi bi-download me-1"></i>Export All Courses</a>
            <a class="btn btn-download" href="{{ route('exports.batch.room') }}"><i class="bi bi-download me-1"></i>Export All Rooms</a>
        </div>
    </div>
</div>
@endsection

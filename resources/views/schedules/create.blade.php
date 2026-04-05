@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Add Schedule</h3>

    @include('partials.errors')

    <form method="POST" action="{{ route('schedules.store') }}" class="card card-body" data-schedule-form>
        @csrf
        @include('schedules.partials.form', ['schedule' => null])
        <button class="btn btn-primary">Save</button>
        <a class="cancel-link mt-3 text-center" href="{{ route('schedules.index') }}">Cancel</a>
    </form>
</div>
@endsection

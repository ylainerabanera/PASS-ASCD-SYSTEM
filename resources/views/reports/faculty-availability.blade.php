@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <div class="text-muted small">Reports</div>
            <h3 class="mb-0">Faculty Availability</h3>
            <div class="text-muted small">Available time slots per day (08:00 AM - 08:00 PM).</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end mb-3">
                <div class="col-md-6">
                    <select id="availability-faculty-select" name="faculty_id" class="form-select searchable-select" data-searchable="true" data-placeholder="Select faculty" onchange="this.form.submit()">
                        <option value="">Select faculty</option>
                        @foreach ($faculties as $item)
                            <option value="{{ $item->id }}" @selected($faculty && $faculty->id === $item->id)>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="text-muted small mt-2">
                        @if ($faculty)
                            Viewing availability for {{ $faculty->name }}.
                        @else
                            Select a faculty to view availability.
                        @endif
                    </div>
                </div>
            </form>

            @if ($faculty)
                <div class="row g-3">
                    @foreach ($days as $day)
                        <div class="col-lg-4 col-md-6">
                            <div class="border rounded-3 p-3 bg-white availability-card">
                                <div class="fw-semibold mb-2">{{ $day }}</div>
                                @php $slots = $availability[$day] ?? collect(); @endphp
                                @if ($slots->isEmpty())
                                    <div class="text-muted small">No available time.</div>
                                @else
                                    <ul class="list-unstyled mb-0 small">
                                        @foreach ($slots as $slot)
                                            <li>{{ $slot }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

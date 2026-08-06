@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Reservation Details</h3>
            <div class="text-muted small">View the details of this room reservation.</div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row gy-2">
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="text-uppercase text-muted mb-1">Room</h6>
                        <p class="mb-0">{{ $roomReservation->room->building_name }} {{ $roomReservation->room->room_name }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100 text-start">
                        <h6 class="text-uppercase text-muted mb-1">Status</h6>
                        @php
                            $statusClasses = [
                                'reserved' => 'badge bg-success py-2 px-3',
                                'pending' => 'badge bg-warning text-dark py-2 px-3',
                                'cancelled' => 'badge bg-danger py-2 px-3',
                            ];
                        @endphp
                        <span class="{{ $statusClasses[$roomReservation->status] ?? 'badge bg-secondary py-2 px-3' }}">{{ ucfirst($roomReservation->status) }}</span>
                    </div>
                </div>
            </div>

            <div class="row gy-2 mt-2">
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="text-uppercase text-muted mb-1">Date</h6>
                        <p class="mb-0">{{ \Carbon\Carbon::parse($roomReservation->date)->format('F d, Y') }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="text-uppercase text-muted mb-1">Start Time</h6>
                        <p class="mb-0">{{ $roomReservation->start_time ? \Carbon\Carbon::parse($roomReservation->start_time)->format('g:ia') : 'All day' }}</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border rounded-3 p-2 h-100">
                        <h6 class="text-uppercase text-muted mb-1">End Time</h6>
                        <p class="mb-0">{{ $roomReservation->end_time ? \Carbon\Carbon::parse($roomReservation->end_time)->format('g:ia') : 'All day' }}</p>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h6 class="text-uppercase text-muted mb-2">Requested By</h6>
                        <p class="mb-0">{{ $roomReservation->requested_by ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100 text-start">
                        <h6 class="text-uppercase text-muted mb-2">Created</h6>
                        <p class="mb-0">{{ $roomReservation->created_at->format('F d, Y g:ia') }}</p>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="border rounded-3 p-2">
                        <h6 class="text-uppercase text-muted mb-2">Note</h6>
                        <p class="mb-0">{{ $roomReservation->note ?? 'No additional notes.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

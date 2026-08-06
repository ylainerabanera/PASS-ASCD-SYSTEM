@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Room Reservations</h3>
            <div class="text-muted small">Temporary room holds and reserved room notices.</div>
        </div>
        <a href="{{ route('room-reservations.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i>Add Reservation
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($reservations->isEmpty())
                <div class="alert alert-info">No room reservations found.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 align-middle text-center">
                        <thead>
                            <tr>
                                <th class="text-start">Room</th>
                                <th class="text-start">Date</th>
                                <th class="text-start">Time</th>
                                <th class="text-start">Requested By</th>
                                <th class="text-start">Note</th>
                                <th class="text-start">Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reservations as $reservation)
                                <tr>
                                    <td>{{ $reservation->room->building_name }} {{ $reservation->room->room_name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reservation->date)->format('M d, Y') }}</td>
                                    <td>
                                        @if ($reservation->start_time && $reservation->end_time)
                                            {{ \Carbon\Carbon::parse($reservation->start_time)->format('g:ia') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:ia') }}
                                        @else
                                            All day
                                        @endif
                                    </td>
                                    <td>{{ $reservation->requested_by ?? 'N/A' }}</td>
                                    @php
                                    $noteText = $reservation->note ? explode(' ', trim($reservation->note))[0] : null;
                                    $noteText = $noteText ? (strlen($noteText) > 15 ? substr($noteText, 0, 15) . '...' : $noteText) : '—';
                                @endphp
                                <td>{{ $noteText }}</td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'reserved' => 'badge bg-success',
                                                'pending' => 'badge bg-warning text-dark',
                                                'cancelled' => 'badge bg-danger',
                                            ];
                                        @endphp
                                        <span class="{{ $statusClasses[$reservation->status] ?? 'badge bg-secondary' }}">{{ ucfirst($reservation->status) }}</span>
                                    </td>
                                    <td>
                                            <a href="{{ route('room-reservations.show', $reservation) }}" class="btn btn-sm btn-info text-white me-1">
                                            <i class="bi bi-eye me-1"></i>View
                                        </a>
                                        <a href="{{ route('room-reservations.edit', $reservation) }}" class="btn btn-sm btn-secondary me-1">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </a>
                                        <form action="{{ route('room-reservations.destroy', $reservation) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" data-confirm="Delete this reservation?">
                                                <i class="bi bi-trash me-1"></i>Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

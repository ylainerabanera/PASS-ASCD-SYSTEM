@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="mb-0">Edit Room Reservation</h3>
            <div class="text-muted small">Update reservation details or change the reservation status.</div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('room-reservations.update', $roomReservation) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="room_id" class="form-label">Room</label>
                    <select id="room_id" name="room_id" class="form-select searchable-select @error('room_id') is-invalid @enderror" data-searchable="true" data-placeholder="Search rooms..." required>
                        <option value="" disabled hidden>Select a room</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->id }}" @selected(old('room_id', $roomReservation->room_id) == $room->id)>{{ $room->building_name }} {{ $room->room_name }}</option>
                        @endforeach
                    </select>
                    @error('room_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" id="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $roomReservation->date) }}" required>
                        @error('date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="start_time" class="form-label">Start Time</label>
                        <input type="time" id="start_time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', $roomReservation->start_time ? \Carbon\Carbon::parse($roomReservation->start_time)->format('H:i') : '') }}">
                        @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="end_time" class="form-label">End Time</label>
                        <input type="time" id="end_time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', $roomReservation->end_time ? \Carbon\Carbon::parse($roomReservation->end_time)->format('H:i') : '') }}">
                        @error('end_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="requested_by" class="form-label">Requested By</label>
                    <input type="text" id="requested_by" name="requested_by" class="form-control @error('requested_by') is-invalid @enderror" value="{{ old('requested_by', $roomReservation->requested_by) }}">
                    @error('requested_by')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="note" class="form-label">Note</label>
                    <textarea id="note" name="note" class="form-control @error('note') is-invalid @enderror" rows="3">{{ old('note', $roomReservation->note) }}</textarea>
                    @error('note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="reserved" @selected(old('status', $roomReservation->status) === 'reserved')>Reserved</option>
                        <option value="pending" @selected(old('status', $roomReservation->status) === 'pending')>Pending</option>
                        <option value="cancelled" @selected(old('status', $roomReservation->status) === 'cancelled')>Cancelled</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">
                    Update Reservation
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

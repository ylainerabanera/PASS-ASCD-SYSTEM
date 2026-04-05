@extends('layouts.app')

@section('content')
<div class="container">
    <h3 class="mb-3">Edit Room</h3>

    @include('partials.errors')

    <form method="POST" action="{{ route('rooms.update', $room) }}" class="card card-body">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Room Name</label>
            <input type="text" name="room_name" class="form-control" value="{{ old('room_name', $room->room_name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Building Name</label>
            <input type="text" name="building_name" class="form-control" value="{{ old('building_name', $room->building_name) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Capacity</label>
            <input type="number" name="capacity" class="form-control" value="{{ old('capacity', $room->capacity) }}" min="0" required>
        </div>
        <button class="btn btn-primary">Update</button>
        <a class="cancel-link mt-3 text-center" href="{{ route('rooms.index') }}">Cancel</a>
    </form>
</div>
@endsection

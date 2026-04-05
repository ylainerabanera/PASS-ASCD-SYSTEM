@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Rooms</h3>
        <a class="btn btn-add" href="{{ route('rooms.create') }}"><i class="bi bi-plus-lg me-1"></i>Add Room</a>
    </div>

    @include('partials.flash')

    <div class="card">
        <div class="card-body">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Room</th>
                        <th>Building</th>
                        <th>Capacity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rooms as $room)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $room->room_name }}</td>
                            <td>{{ $room->building_name }}</td>
                            <td>{{ $room->capacity }}</td>
                            <td>
                                <a class="btn btn-sm btn-edit" href="{{ route('rooms.edit', $room) }}"><i class="bi bi-pencil-square me-1"></i>Edit</a>
                                <a class="btn btn-sm btn-view" href="{{ route('timetables.room', $room) }}"><i class="bi bi-calendar3 me-1"></i>Timetable</a>
                                <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-delete" onclick="return confirm('Delete this room?')"><i class="bi bi-trash me-1"></i>Delete</button>
                                </form>
                                <a class="btn btn-sm btn-download" href="{{ route('exports.room.pdf', $room) }}" target="_blank" rel="noopener"><i class="bi bi-file-earmark-pdf me-1"></i>PDF</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No rooms yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

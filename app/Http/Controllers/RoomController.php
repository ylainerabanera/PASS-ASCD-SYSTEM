<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::query()->orderBy('id')->get();

        return view('rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_name' => ['required', 'string', 'max:255', 'unique:rooms,room_name'],
            'building_name' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:0'],
        ]);

        Room::create($data);

        return redirect()->route('rooms.index')->with('status', 'Room created successfully.');
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $data = $request->validate([
            'room_name' => ['required', 'string', 'max:255', 'unique:rooms,room_name,' . $room->id],
            'building_name' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:0'],
        ]);

        $room->update($data);

        return redirect()->route('rooms.index')->with('status', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()->route('rooms.index')->with('status', 'Room deleted successfully.');
    }
}

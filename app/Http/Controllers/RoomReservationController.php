<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomReservation;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class RoomReservationController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('room_reservations')) {
            return view('room_reservations.index', [
                'reservations' => collect(),
            ]);
        }

        $reservations = RoomReservation::with('room')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return view('room_reservations.index', compact('reservations'));
    }

    public function create()
    {
        if (!Schema::hasTable('room_reservations')) {
            abort(404);
        }

        $rooms = Room::orderBy('building_name')->orderBy('room_name')->get();

        return view('room_reservations.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('room_reservations')) {
            abort(404);
        }

        $data = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i', 'required_with:end_time'],
            'end_time' => ['nullable', 'date_format:H:i', 'required_with:start_time', 'after:start_time'],
            'requested_by' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $validator = Validator::make($data, []);
        $validator->after(function ($validator) use ($data) {
            if ($this->reservationConflicts($data)) {
                $validator->errors()->add('start_time', 'This reservation conflicts with an existing booking.');
            }
        });

        $validator->validate();

        RoomReservation::create($data + ['status' => 'reserved']);

        return redirect()->route('room-reservations.index')->with('status', 'Room reservation added successfully.');
    }

    public function edit(RoomReservation $roomReservation)
    {
        if (!Schema::hasTable('room_reservations')) {
            abort(404);
        }

        $rooms = Room::orderBy('building_name')->orderBy('room_name')->get();

        return view('room_reservations.edit', compact('roomReservation', 'rooms'));
    }

    public function update(Request $request, RoomReservation $roomReservation)
    {
        if (!Schema::hasTable('room_reservations')) {
            abort(404);
        }

        $data = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'date' => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i', 'required_with:end_time'],
            'end_time' => ['nullable', 'date_format:H:i', 'required_with:start_time', 'after:start_time'],
            'requested_by' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', 'in:reserved,pending,cancelled'],
        ]);

        $validator = Validator::make($data, []);
        $validator->after(function ($validator) use ($data, $roomReservation) {
            if ($this->reservationConflicts($data, $roomReservation)) {
                $validator->errors()->add('start_time', 'This reservation conflicts with an existing booking.');
            }
        });

        $validator->validate();

        $roomReservation->update($data);

        return redirect()->route('room-reservations.index')->with('status', 'Room reservation updated successfully.');
    }

    public function show(RoomReservation $roomReservation)
    {
        if (!Schema::hasTable('room_reservations')) {
            abort(404);
        }

        return view('room_reservations.show', compact('roomReservation'));
    }

    protected function reservationConflicts(array $data, RoomReservation $ignoreReservation = null): bool
    {
        $query = RoomReservation::where('room_id', $data['room_id'])
            ->where('date', $data['date']);

        if ($ignoreReservation) {
            $query->where('id', '<>', $ignoreReservation->id);
        }

        if (empty($data['start_time']) || empty($data['end_time'])) {
            return $query->exists();
        }

        return $query->where(function ($query) use ($data) {
            $query->whereNull('start_time')
                ->whereNull('end_time');
        })->orWhere(function ($query) use ($data) {
            $query->whereNotNull('start_time')
                ->whereNotNull('end_time')
                ->where('start_time', '<', $data['end_time'])
                ->where('end_time', '>', $data['start_time']);
        })->exists();
    }

    public function destroy(RoomReservation $roomReservation)
    {
        if (!Schema::hasTable('room_reservations')) {
            abort(404);
        }

        $roomReservation->delete();

        return redirect()->route('room-reservations.index')->with('status', 'Room reservation deleted successfully.');
    }
}

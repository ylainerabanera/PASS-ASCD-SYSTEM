<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index()
    {
        $faculties = Faculty::query()->orderBy('id')->get();

        return view('faculties.index', compact('faculties'));
    }

    public function create()
    {
        return view('faculties.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Faculty::create($data);

        return redirect()->route('faculties.index')->with('status', 'Faculty created successfully.');
    }

    public function edit(Faculty $faculty)
    {
        return view('faculties.edit', compact('faculty'));
    }

    public function update(Request $request, Faculty $faculty)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $faculty->update($data);

        return redirect()->route('faculties.index')->with('status', 'Faculty updated successfully.');
    }

    public function destroy(Faculty $faculty)
    {
        $faculty->delete();

        return redirect()->route('faculties.index')->with('status', 'Faculty deleted successfully.');
    }
}

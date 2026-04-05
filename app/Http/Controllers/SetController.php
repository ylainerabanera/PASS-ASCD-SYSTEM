<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Set;
use Illuminate\Http\Request;

class SetController extends Controller
{
    public function index()
    {
        $sets = Set::with('course')->orderBy('id')->get();

        return view('sets.index', compact('sets'));
    }

    public function create()
    {
        $courses = Course::query()->orderBy('name')->get();

        return view('sets.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'year_level' => ['required', 'integer', 'between:1,4'],
            'set_code' => ['nullable', 'string', 'size:1', 'in:A,B,C,D,E,F'],
            'student_count' => ['required', 'integer', 'min:0'],
        ]);

        Set::create($data);

        return redirect()->route('sets.index')->with('status', 'Set created successfully.');
    }

    public function edit(Set $set)
    {
        $courses = Course::query()->orderBy('name')->get();

        return view('sets.edit', compact('set', 'courses'));
    }

    public function update(Request $request, Set $set)
    {
        $data = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'year_level' => ['required', 'integer', 'between:1,4'],
            'set_code' => ['nullable', 'string', 'size:1', 'in:A,B,C,D,E,F'],
            'student_count' => ['required', 'integer', 'min:0'],
        ]);

        $set->update($data);

        return redirect()->route('sets.index')->with('status', 'Set updated successfully.');
    }

    public function destroy(Set $set)
    {
        $set->delete();

        return redirect()->route('sets.index')->with('status', 'Set deleted successfully.');
    }
}

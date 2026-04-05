<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::query()->orderBy('id')->get();

        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_code' => ['required', 'string', 'max:50', 'unique:subjects,subject_code'],
            'subject_name' => ['required', 'string', 'max:255'],
            'units' => ['required', 'integer', 'min:1', 'max:9'],
        ]);

        Subject::create($data);

        return redirect()->route('subjects.index')->with('status', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'subject_code' => ['required', 'string', 'max:50', 'unique:subjects,subject_code,' . $subject->id],
            'subject_name' => ['required', 'string', 'max:255'],
            'units' => ['required', 'integer', 'min:1', 'max:9'],
        ]);

        $subject->update($data);

        return redirect()->route('subjects.index')->with('status', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('subjects.index')->with('status', 'Subject deleted successfully.');
    }
}

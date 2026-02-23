<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('name')->paginate(10);

        return view('admin.subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('admin.subjects.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:subjects,name'],
        ]);

        Subject::create($data);

        return redirect()->route('admin.subjects.index')
            ->with('status', 'Subject created successfully.');
    }

    public function edit(Subject $subject)
    {
        return view('admin.subjects.edit', compact('subject'));
    }

    public function show(Subject $subject)
    {
        $questions = $subject->questions()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.subjects.show', compact('subject', 'questions'));
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:subjects,name,'.$subject->id],
        ]);

        $subject->update($data);

        return redirect()->route('admin.subjects.index')
            ->with('status', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('status', 'Subject deleted successfully.');
    }
}

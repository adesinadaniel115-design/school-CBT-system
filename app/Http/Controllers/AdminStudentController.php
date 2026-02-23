<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminStudentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_admin', false)
            ->withCount(['examSessions' => function ($q) {
                $q->whereNotNull('completed_at');
            }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->latest()->paginate(15);
        
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $subjects = Subject::all();
        return view('admin.students.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $student = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => false,
        ]);

        return redirect()->route('admin.students.index')
            ->with('status', 'Student created successfully!');
    }

    public function edit(User $student)
    {
        if ($student->is_admin) {
            abort(403, 'Cannot edit admin users');
        }

        $subjects = Subject::all();
        return view('admin.students.edit', compact('student', 'subjects'));
    }

    public function update(Request $request, User $student)
    {
        if ($student->is_admin) {
            abort(403, 'Cannot edit admin users');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($student->id)],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $student->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->filled('password')) {
            $student->update([
                'password' => Hash::make($validated['password'])
            ]);
        }

        return redirect()->route('admin.students.index')
            ->with('status', 'Student updated successfully!');
    }

    public function destroy(User $student)
    {
        if ($student->is_admin) {
            abort(403, 'Cannot delete admin users');
        }

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('status', 'Student deleted successfully!');
    }

    public function show(User $student)
    {
        if ($student->is_admin) {
            abort(403);
        }

        $student->load([
            'examSessions' => function ($query) {
                $query->whereNotNull('completed_at')
                    ->with(['subject', 'examSubjectScores.subject'])
                    ->orderBy('completed_at', 'desc');
            }
        ]);

        $stats = [
            'total_exams' => $student->examSessions->count(),
            'school_exams' => $student->examSessions->where('exam_mode', 'school')->count(),
            'jamb_exams' => $student->examSessions->where('exam_mode', 'jamb')->count(),
            'avg_school_score' => $student->examSessions->where('exam_mode', 'school')
                ->avg(function ($session) {
                    return ($session->score / $session->total_questions) * 100;
                }),
            'avg_jamb_score' => $student->examSessions->where('exam_mode', 'jamb')->avg('score'),
        ];

        return view('admin.students.show', compact('student', 'stats'));
    }
}

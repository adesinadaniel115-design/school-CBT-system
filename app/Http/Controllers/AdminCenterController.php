<?php

namespace App\Http\Controllers;

use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminCenterController extends Controller
{
    public function index(Request $request)
    {
        $query = Center::withCount('students');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
        }

        $centers = $query->latest()->paginate(15);

        return view('admin.centers.index', compact('centers'));
    }

    public function create()
    {
        return view('admin.centers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:centers,name'],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'contact_email' => ['nullable', 'email'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
        ]);

        Center::create($validated);

        return redirect()->route('admin.centers.index')
            ->with('status', 'Center created successfully!');
    }

    public function edit(Center $center)
    {
        return view('admin.centers.edit', compact('center'));
    }

    public function update(Request $request, Center $center)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('centers')->ignore($center->id)],
            'location' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'contact_email' => ['nullable', 'email'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
        ]);

        $center->update($validated);

        return redirect()->route('admin.centers.index')
            ->with('status', 'Center updated successfully!');
    }

    public function destroy(Center $center)
    {
        $name = $center->name;
        $center->delete();

        return redirect()->route('admin.centers.index')
            ->with('status', "Center '{$name}' deleted successfully!");
    }

    /**
     * Get students for a specific center (for selection in forms)
     */
    public function getStudents(Center $center)
    {
        $students = $center->students()
            ->where('is_admin', false)
            ->select('id', 'name', 'email', 'student_id')
            ->get();

        return response()->json(['students' => $students]);
    }
}

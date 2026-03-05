<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('price')->paginate(10);
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255','unique:plans,name'],
            'price' => ['required','numeric','min:0'],
            'attempts_allowed' => ['required','integer','min:0'],
            'duration_days' => ['nullable','integer','min:0'],
            'school_questions' => ['nullable','integer','min:0'],
            'jamb_questions_per_subject' => ['nullable','integer','min:0'],
            'jamb_english_questions' => ['nullable','integer','min:0'],
            // checkboxes send "on" by default; validator boolean rule rejects that
            // so we only validate presence and convert below using boolean().
            'has_explanations' => ['sometimes'],
            'has_leaderboard' => ['sometimes'],
            'has_streak' => ['sometimes'],
        ]);

        // convert to actual booleans; boolean() understands "on"/"1"/"true"
        $data['has_explanations'] = $request->boolean('has_explanations');
        $data['has_leaderboard'] = $request->boolean('has_leaderboard');
        $data['has_streak'] = $request->boolean('has_streak');

        Plan::create($data);

        return redirect()->route('admin.plans.index')
            ->with('status','Plan created successfully.');
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255','unique:plans,name,'.$plan->id],
            'price' => ['required','numeric','min:0'],
            'attempts_allowed' => ['required','integer','min:0'],
            'duration_days' => ['nullable','integer','min:0'],
            'school_questions' => ['nullable','integer','min:0'],
            'jamb_questions_per_subject' => ['nullable','integer','min:0'],
            'jamb_english_questions' => ['nullable','integer','min:0'],
            'has_explanations' => ['sometimes'],
            'has_leaderboard' => ['sometimes'],
            'has_streak' => ['sometimes'],
        ]);

        $data['has_explanations'] = $request->boolean('has_explanations');
        $data['has_leaderboard'] = $request->boolean('has_leaderboard');
        $data['has_streak'] = $request->boolean('has_streak');

        $plan->update($data);

        return redirect()->route('admin.plans.index')
            ->with('status','Plan updated successfully.');
    }

    public function destroy(Plan $plan)
    {
        $plan->delete();
        return redirect()->route('admin.plans.index')
            ->with('status','Plan deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ExamToken;
use Illuminate\Http\Request;

class AdminExamTokenController extends Controller
{
    public function index(Request $request)
    {
<<<<<<< HEAD
        $query = ExamToken::with(['creator', 'usages.user', 'center']);
=======
        $includePlan = \Schema::hasTable('plans');
        $query = ExamToken::with(['creator', 'usages.user', 'center']);
        if ($includePlan) {
            $query->with('plan');
        }
>>>>>>> origin/backup-main

        if ($request->filled('search')) {
            $query->where('code', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('expires_at', '<', now());
            } elseif ($request->status === 'used_up') {
                $query->whereColumn('used_count', '>=', 'max_uses');
            }
        }

        if ($request->filled('center_id')) {
            $query->where('center_id', $request->center_id);
        }

        $tokens = $query->latest()->paginate(20);
        $centers = \App\Models\Center::orderBy('name')->get();

<<<<<<< HEAD
        return view('admin.tokens.index', compact('tokens', 'centers'));
=======
        return view('admin.tokens.index', compact('tokens', 'centers', 'includePlan'));
>>>>>>> origin/backup-main
    }

    public function create()
    {
        $centers = \App\Models\Center::orderBy('name')->get();
<<<<<<< HEAD
        return view('admin.tokens.create', compact('centers'));
=======
        $plans = [];

        if (\Schema::hasTable('plans')) {
            $plans = \App\Models\Plan::orderBy('price')->get();
        }

        return view('admin.tokens.create', compact('centers', 'plans'));
>>>>>>> origin/backup-main
    }

    public function store(Request $request)
    {
        $rules = [
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'expires_at' => ['nullable', 'date', 'after:today'],
            'notes' => ['nullable', 'string', 'max:500'],
            'center_id' => ['nullable','exists:centers,id'],
<<<<<<< HEAD
        ]);
=======
        ];

        if (\Schema::hasTable('plans')) {
            // Plan-first flow: require selecting a plan; token usage limits inherit from plan
            $rules['plan_id'] = ['required','exists:plans,id'];
        }

        $validated = $request->validate($rules);
>>>>>>> origin/backup-main

        $tokens = [];
        $planValue = null;
        $planAttempts = 1;
        if (\Schema::hasTable('plans')) {
            $planValue = $validated['plan_id'];
            $planModel = \App\Models\Plan::findOrFail($planValue);
            $planAttempts = $planModel->attempts_allowed;
        }

        for ($i = 0; $i < $validated['quantity']; $i++) {
            $tokens[] = ExamToken::create([
                'code' => ExamToken::generateCode(),
                'max_uses' => $planAttempts,
                'created_by' => auth()->id(),
                'expires_at' => $validated['expires_at'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'center_id' => $validated['center_id'] ?? null,
<<<<<<< HEAD
=======
                'plan_id' => $planValue,
>>>>>>> origin/backup-main
            ]);
        }

        if ($validated['quantity'] === 1) {
            return redirect()->route('admin.tokens.index')
                ->with('status', "Token created: {$tokens[0]->code}");
        }

        return redirect()->route('admin.tokens.print', ['ids' => collect($tokens)->pluck('id')->implode(',')])
            ->with('status', "{$validated['quantity']} tokens generated successfully!");
    }

    public function toggle(ExamToken $token)
    {
        $token->update(['is_active' => !$token->is_active]);

        $status = $token->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.tokens.index')
            ->with('status', "Token {$token->code} has been {$status}");
    }

    public function destroy(ExamToken $token)
    {
        $code = $token->code;
        $token->delete();

        return redirect()->route('admin.tokens.index')
            ->with('status', "Token {$code} deleted successfully");
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:unused,used,expired,fully_used']
        ]);

        $type = $request->type;
        $query = ExamToken::query();

        switch ($type) {
            case 'unused':
                $query->where('used_count', 0);
                break;
            case 'used':
                $query->where('used_count', '>', 0);
                break;
            case 'expired':
                $query->where('expires_at', '<', now());
                break;
            case 'fully_used':
                $query->whereColumn('used_count', '>=', 'max_uses');
                break;
        }

        $count = $query->count();
        $query->delete();

        $typeLabel = match($type) {
            'unused' => 'unused',
            'used' => 'used',
            'expired' => 'expired',
            'fully_used' => 'fully used'
        };

        return redirect()->route('admin.tokens.index')
            ->with('status', "Successfully deleted {$count} {$typeLabel} token(s)");
    }

    public function print(Request $request)
    {
        // If 'all' parameter is present, print all tokens (with filters)
        if ($request->has('all')) {
            // include center for print
            $query = ExamToken::with(['creator', 'center']);

            // Apply same filters as index page
            if ($request->filled('search')) {
                $query->where('code', 'like', "%{$request->search}%");
            }

            if ($request->filled('status')) {
                if ($request->status === 'active') {
                    $query->where('is_active', true);
                } elseif ($request->status === 'inactive') {
                    $query->where('is_active', false);
                } elseif ($request->status === 'expired') {
                    $query->where('expires_at', '<', now());
                } elseif ($request->status === 'used_up') {
                    $query->whereColumn('used_count', '>=', 'max_uses');
                }
            }

            if ($request->filled('center_id')) {
                $query->where('center_id', $request->center_id);
            }

            $tokens = $query->latest()->get();
        } else {
            // Print specific tokens by IDs
            $ids = explode(',', $request->ids);
            $tokens = ExamToken::whereIn('id', $ids)->get();
        }

        return view('admin.tokens.print', compact('tokens'));
    }

    public function validate(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string']
        ]);

        $token = ExamToken::where('code', strtoupper($request->code))->first();

        if (!$token) {
            return response()->json([
                'valid' => false,
                'message' => 'Token not found'
            ], 404);
        }

        if (!$token->isValid()) {
            $reason = !$token->is_active ? 'deactivated' :
                     ($token->expires_at && $token->expires_at->isPast() ? 'expired' : 'fully used');
            
            return response()->json([
                'valid' => false,
                'message' => "Token is {$reason}"
            ], 400);
        }

        $response = [
            'valid' => true,
            'token' => [
                'code' => $token->code,
                'remaining_uses' => $token->remainingUses(),
                'expires_at' => $token->expires_at?->format('M d, Y'),
            ]
        ];

        if (\Schema::hasTable('plans') && $token->plan) {
            $response['token']['plan'] = [
                'name' => $token->plan->name,
                'price' => $token->plan->price,
                'attempts_allowed' => $token->plan->attempts_allowed,
                'duration_days' => $token->plan->duration_days,
                'has_explanations' => $token->plan->has_explanations,
                'has_leaderboard' => $token->plan->has_leaderboard,
                'has_streak' => $token->plan->has_streak,
            ];
        }

        return response()->json($response);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use Illuminate\Http\Request;

class StudentLeaderboardController extends Controller
{
    public function index(Request $request)
    {
        // only students with the leaderboard feature may view
        if (! auth()->user()->hasFeature('leaderboard')) {
            abort(403);
        }

        // get top 10 completed exam sessions ordered by score
        $leaders = ExamSession::with('student')
            ->whereNotNull('completed_at')
            ->orderByDesc('score')
            ->take(10)
            ->get();

        // determine the current user's best completed session and rank
        $userRank = null;
        $userSession = null;
        $userId = auth()->id();

        $best = ExamSession::where('student_id', $userId)
            ->whereNotNull('completed_at')
            ->orderByDesc('score')
            ->orderBy('id')
            ->first();

        if ($best) {
            // compute rank (1-based) including ties broken by lower id
            $higherCount = ExamSession::whereNotNull('completed_at')
                ->where(function ($q) use ($best) {
                    $q->where('score', '>', $best->score)
                      ->orWhere(function ($q2) use ($best) {
                          $q2->where('score', $best->score)
                             ->where('id', '<', $best->id);
                      });
                })
                ->count();

            $userRank = $higherCount + 1;
            $userSession = $best;

            // if user is among top 10 we'll already have them in $leaders
            // if they are outside and not too far down (<=30) we will display them later
        }

        return view('student.leaderboard', compact('leaders', 'userRank', 'userSession'));
    }
}

@extends('layouts.admin')

@section('title', 'Plans')
@section('page-title', 'Subscription Plans')

@section('content')
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-header">
        <h3 class="card-title"><i class="bi bi-list-check"></i> Plans</h3>
        <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Plan
        </a>
    </div>
</div>

<div class="card">
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Attempts</th>
                    <th>Duration</th>
                    <th>Features</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                    <tr>
                        <td>{{ $plan->name }}</td>
                        <td>${{ number_format($plan->price,2) }}</td>
                        <td>{{ $plan->attempts_allowed }}</td>
                        <td>{{ $plan->duration_days ? $plan->duration_days.' days' : 'Unlimited' }}</td>
                        <td>
                            @if($plan->has_explanations) <span class="badge bg-info">Explanation</span>@endif
                            @if($plan->has_leaderboard) <span class="badge bg-success">Leaderboard</span>@endif
                            @if($plan->has_streak) <span class="badge bg-warning text-dark">Streak</span>@endif
                        </td>
                        <td>
                            <a href="{{ route('admin.plans.edit', $plan) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" style="display:inline;" onsubmit="return confirm('Delete this plan?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No plans defined yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $plans->links() }}
    </div>
</div>
@endsection

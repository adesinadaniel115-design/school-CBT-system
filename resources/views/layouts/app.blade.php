<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>School CBT</title>
    <style>
        body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; background: #f5f7fb; margin: 0; color: #1f2933; }
        header { background: #0f172a; color: #fff; padding: 16px 24px; }
        header a { color: #fff; margin-right: 16px; text-decoration: none; }
        main { padding: 24px; max-width: 1100px; margin: 0 auto; }
        .card { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 12px rgba(15, 23, 42, 0.08); margin-bottom: 20px; }
        .grid { display: grid; gap: 16px; }
        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
        .btn { background: #2563eb; color: #fff; border: none; padding: 10px 16px; border-radius: 6px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn.secondary { background: #475569; }
        .btn.danger { background: #dc2626; }
        .btn.light { background: #e2e8f0; color: #0f172a; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 10px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        .alert { background: #fee2e2; color: #7f1d1d; padding: 10px 14px; border-radius: 6px; margin-bottom: 16px; }
        .success { background: #dcfce7; color: #14532d; }
        .field { margin-bottom: 14px; }
        label { display: block; font-weight: 600; margin-bottom: 6px; }
        input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="date"], select, textarea { width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #cbd5f5; background: #fff; }
        textarea { min-height: 90px; }
        .pill { display: inline-block; padding: 4px 10px; background: #e2e8f0; border-radius: 999px; font-size: 12px; }
        .timer { font-size: 18px; font-weight: 700; color: #b91c1c; }
        .pagination { margin-top: 16px; }
        .nav-right { float: right; }
        .muted { color: #64748b; }
    </style>
</head>
<body>
<header>
    <a href="/">School CBT</a>
    @auth
        <a href="{{ route('student.dashboard') }}">Dashboard</a>
        <a href="{{ route('student.history') }}">History</a>
        @if(auth()->user()->is_admin)
            <a href="{{ route('admin.subjects.index') }}">Admin Subjects</a>
            <a href="{{ route('admin.questions.index') }}">Admin Questions</a>
            <a href="{{ route('admin.reports.index') }}">Reports</a>
        @endif
        <span class="nav-right">
            {{ auth()->user()->name }}
            <form method="POST" action="{{ route('logout') }}" style="display:inline-block; margin-left: 8px;">
                @csrf
                <button class="btn light" type="submit">Logout</button>
            </form>
        </span>
    @endauth
</header>
<main>
    @if(session('status'))
        <div class="alert success">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="alert">
            <ul style="margin:0; padding-left: 18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<!-- Footer with Copyright -->
<footer style="background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 1rem 2rem; text-align: center; margin-top: 2rem;">
    <p style="margin: 0; color: #6b7280; font-size: 0.875rem;">
        <strong style="color: #1f2937;">CBT Platform</strong> © 2026 El-Bethel Digital Learning Systems.
    </p>
</footer>

</body>
</html>

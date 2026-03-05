<aside class="sidebar">
    <div class="sidebar-brand">
        <h4><i class="bi bi-mortarboard-fill"></i> <span>School CBT</span></h4>
        <p>Computer Based Testing Platform</p>
    </div>

    <nav class="sidebar-menu">
        <a href="{{ route('student.dashboard') }}" class="menu-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
            <span class="menu-icon"><i class="bi bi-house-door-fill"></i></span>
            <span class="menu-label">Dashboard</span>
        </a>
        <a href="{{ route('student.history') }}" class="menu-item {{ request()->routeIs('student.history') ? 'active' : '' }}">
            <span class="menu-icon"><i class="bi bi-clock-history"></i></span>
            <span class="menu-label">Exam History</span>
        </a>

        <a href="{{ route('student.plans') }}" class="menu-item {{ request()->routeIs('student.plans') ? 'active' : '' }}">
            <span class="menu-icon"><i class="bi bi-credit-card"></i></span>
            <span class="menu-label">Plans &amp; Tokens</span>
        </a>

        @if(auth()->user()->hasFeature('leaderboard'))
            <a href="{{ route('student.leaderboard') }}" class="menu-item {{ request()->routeIs('student.leaderboard') ? 'active' : '' }}">
                <span class="menu-icon"><i class="bi bi-trophy-fill"></i></span>
                <span class="menu-label">Leaderboard</span>
            </a>
        @endif

        <a href="{{ route('student.profile.edit') }}" class="menu-item {{ request()->routeIs('student.profile.*') ? 'active' : '' }}">
            <span class="menu-icon"><i class="bi bi-person-circle"></i></span>
            <span class="menu-label">Profile</span>
        </a>
        <div style="margin-top: 2rem; padding: 0 1rem;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="menu-item logout w-100 border-0">
                    <span class="menu-icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span class="menu-label">Logout</span>
                </button>
            </form>
        </div>
    </nav>
</aside>
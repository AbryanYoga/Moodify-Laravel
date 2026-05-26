<style>
    /* Phosphor Icons */
    @import url('https://unpkg.com/@phosphor-icons/web@2.1.1/src/fill/style.css');
    @import url('https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css');

    .custom-navbar {
        position: sticky;
        top: 0;
        width: 100%;
        height: 72px;
        background: rgba(9, 9, 11, 0.7);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 2rem;
        z-index: 1000;
        font-family: 'Sora', 'Poppins', sans-serif;
    }

    .custom-nav-left {
        display: flex;
        align-items: center;
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.05em;
        color: #fff;
        text-decoration: none;
        gap: 8px;
    }

    .custom-nav-left i {
        color: #c026d3; /* gradient-1 */
    }

    .custom-nav-center {
        display: flex;
        gap: 32px;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
    }

    .custom-nav-link {
        color: #a1a1aa; /* text-secondary */
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        position: relative;
        padding: 8px 0;
    }

    .custom-nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0%;
        height: 2px;
        background: linear-gradient(90deg, #c026d3, #db2777);
        transition: width 0.3s ease;
        border-radius: 2px;
    }

    .custom-nav-link:hover {
        color: #fff;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
    }

    .custom-nav-link:hover::after,
    .custom-nav-link.active::after {
        width: 100%;
    }

    .custom-nav-link.active {
        color: #fff;
    }

    .custom-nav-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .custom-user-name {
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #fff;
    }

    .custom-user-avatar {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #c026d3, #db2777);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; font-weight: 700; color: #fff;
    }

    .custom-btn-logout {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.05);
        color: #fff;
        padding: 8px 16px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .custom-btn-logout:hover {
        background: rgba(244, 63, 94, 0.1); /* danger with opacity */
        border-color: rgba(244, 63, 94, 0.3);
        color: #f43f5e;
        box-shadow: 0 0 15px rgba(244, 63, 94, 0.2);
    }

    /* Mobile Menu */
    .mobile-menu-btn {
        display: none;
        background: transparent;
        border: none;
        color: #fff;
        font-size: 1.5rem;
        cursor: pointer;
    }

    .mobile-menu {
        display: none;
        position: absolute;
        top: 72px;
        left: 0;
        width: 100%;
        background: rgba(9, 9, 11, 0.95);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        flex-direction: column;
        padding: 1rem 2rem;
        gap: 16px;
    }

    .mobile-menu.active {
        display: flex;
    }

    @media (max-width: 768px) {
        .custom-nav-center { display: none; }
        .custom-user-name span { display: none; }
        .mobile-menu-btn { display: block; }
        .custom-nav-right { gap: 12px; }
        .custom-btn-logout span { display: none; }
    }
</style>

<nav class="custom-navbar">
    <a href="{{ url('/') }}" class="custom-nav-left">
        <i class="ph-fill ph-music-notes"></i> Moodify
    </a>
    
    <div class="custom-nav-center">
        <a href="{{ url('/mood') }}" class="custom-nav-link {{ request()->is('mood') ? 'active' : '' }}">Home</a>
        <a href="{{ url('/dashboard-mood') }}" class="custom-nav-link {{ request()->is('dashboard-mood') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('favorite.index') }}" class="custom-nav-link {{ request()->routeIs('favorite.index') ? 'active' : '' }}">Favorites</a>
    </div>

    <div class="custom-nav-right">
        @auth
        <div class="custom-user-name">
            <div class="custom-user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
            <span>{{ Auth::user()->name }}</span>
        </div>
        
        <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" class="custom-btn-logout">
                <i class="ph ph-sign-out"></i> <span>Logout</span>
            </button>
        </form>
        @endauth

        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
            <i class="ph ph-list"></i>
        </button>
    </div>
</nav>

<div class="mobile-menu" id="mobileMenu">
    <a href="{{ url('/mood') }}" class="custom-nav-link {{ request()->is('mood') ? 'active' : '' }}">Home</a>
    <a href="{{ url('/dashboard-mood') }}" class="custom-nav-link {{ request()->is('dashboard-mood') ? 'active' : '' }}">Dashboard</a>
    <a href="{{ route('favorite.index') }}" class="custom-nav-link {{ request()->routeIs('favorite.index') ? 'active' : '' }}">Favorites</a>
</div>

<script>
    function toggleMobileMenu() {
        document.getElementById('mobileMenu').classList.toggle('active');
    }
</script>

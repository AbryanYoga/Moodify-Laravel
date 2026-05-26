<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Moodify</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        :root {
            --bg-dark: #0a0a0a;
            --bg-card: rgba(255, 255, 255, 0.05);
            --bg-card-hover: rgba(255, 255, 255, 0.1);
            --border-color: rgba(255, 255, 255, 0.1);
            --text-primary: #ffffff;
            --text-secondary: #a0a0a0;
            --accent: #1db954;
            --accent-hover: #1ed760;
            --danger: #ef4444;
            --danger-hover: #dc2626;
            --glow: 0 0 20px rgba(29, 185, 84, 0.2);
            --glass-bg: rgba(10, 10, 10, 0.7);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Figtree', sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--bg-card);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 50;
            backdrop-filter: blur(10px);
        }

        .sidebar-header {
            padding: 24px;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent);
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .nav-links {
            padding: 24px 12px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

        .nav-link {
            padding: 12px 16px;
            border-radius: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background: var(--bg-card-hover);
            color: var(--text-primary);
        }

        .nav-link.active {
            color: var(--accent);
            background: rgba(29, 185, 84, 0.1);
        }

        .sidebar-footer {
            padding: 24px;
            border-top: 1px solid var(--border-color);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            height: 72px;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .page-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-profile img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
        }

        .content-area {
            padding: 32px;
            flex: 1;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        /* Buttons & Forms Shared */
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 0.95rem;
        }

        .btn-primary {
            background: var(--accent);
            color: #000;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
            box-shadow: var(--glow);
            transform: translateY(-2px);
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .btn-danger:hover {
            background: var(--danger);
            color: white;
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.2);
        }

        .btn-secondary {
            background: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--bg-card-hover);
        }

        /* Toast Component */
        .toast-container {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .toast {
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            padding: 16px 24px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
            transform: translateX(120%);
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast.success i { color: var(--accent); }
        .toast.error i { color: var(--danger); }

        /* Smooth page transitions */
        .fade-enter {
            opacity: 0;
            transform: translateY(10px);
            animation: fadeIn 0.4s ease forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* =====================
           RESPONSIVE DESIGN
           ===================== */
        
        /* Tablet (max-width: 1024px) */
        @media (max-width: 1024px) {
            .sidebar {
                width: 220px;
            }
            .main-content {
                margin-left: 220px;
            }
            .content-area {
                padding: 24px;
            }
        }

        /* Mobile & Tablet Portrait (max-width: 768px) */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }
            .sidebar-header {
                padding: 20px;
                font-size: 1.3rem;
            }
            .nav-links {
                flex-direction: row;
                padding: 16px 12px;
                overflow-x: auto;
                gap: 12px;
            }
            .nav-link {
                white-space: nowrap;
                padding: 10px 16px;
                font-size: 0.9rem;
            }
            .sidebar-footer {
                padding: 16px 20px;
            }
            .main-content {
                margin-left: 0;
            }
            .top-navbar {
                height: 64px;
                padding: 0 20px;
            }
            .page-title {
                font-size: 1.1rem;
            }
            .content-area {
                padding: 20px 16px;
            }
            .user-profile img {
                width: 32px;
                height: 32px;
            }
            .user-profile div {
                font-size: 0.85rem;
            }
        }

        /* Mobile Portrait (max-width: 480px) */
        @media (max-width: 480px) {
            .sidebar-header {
                padding: 16px;
                font-size: 1.2rem;
                gap: 10px;
            }
            .nav-links {
                padding: 12px 8px;
                gap: 8px;
            }
            .nav-link {
                padding: 8px 14px;
                font-size: 0.85rem;
                gap: 8px;
            }
            .sidebar-footer {
                padding: 12px 16px;
            }
            .user-profile {
                gap: 10px;
            }
            .user-profile img {
                width: 28px;
                height: 28px;
            }
            .user-profile div:first-of-type {
                font-size: 0.8rem;
            }
            .user-profile div:last-of-type {
                font-size: 0.75rem;
            }
            .top-navbar {
                height: 56px;
                padding: 0 16px;
            }
            .page-title {
                font-size: 1rem;
            }
            .content-area {
                padding: 16px 12px;
            }
            .btn {
                padding: 8px 16px;
                font-size: 0.9rem;
                gap: 6px;
            }
            .toast-container {
                bottom: 16px;
                right: 16px;
                left: 16px;
            }
            .toast {
                padding: 14px 20px;
                font-size: 0.9rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class="ph-fill ph-music-notes"></i>
            Moodify Admin
        </div>
        <nav class="nav-links">
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="ph ph-squares-four"></i> Dashboard
            </a>
            <a href="{{ route('admin.moods.index') }}" class="nav-link {{ request()->routeIs('admin.moods.*') ? 'active' : '' }}">
                <i class="ph ph-list-dashes"></i> Manage Moods
            </a>
            <a href="{{ url('/mood') }}" class="nav-link">
                <i class="ph ph-arrow-left"></i> Back to App
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="user-profile">
                <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" alt="Avatar">
                <div>
                    <div style="font-weight: 600; font-size: 0.9rem;">{{ Auth::user()->name }}</div>
                    <div style="color: var(--text-secondary); font-size: 0.8rem;">Administrator</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="top-navbar">
            <h1 class="page-title">@yield('title')</h1>
            <div>
                <!-- Extra topnav items if needed -->
            </div>
        </header>

        <div class="content-area fade-enter">
            @yield('content')
        </div>
    </main>

    <!-- Toast Notifications -->
    <div id="toast-container" class="toast-container"></div>

    <script>
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const icon = type === 'success' ? 'ph-check-circle' : 'ph-warning-circle';
            toast.innerHTML = `<i class="ph-fill ${icon}" style="font-size: 1.25rem;"></i> <span>${message}</span>`;
            
            container.appendChild(toast);
            
            setTimeout(() => toast.classList.add('show'), 10);
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 400);
            }, 3000);
        }

        // Show session flashes automatically
        @if(session('success'))
            document.addEventListener('DOMContentLoaded', () => showToast("{{ session('success') }}", 'success'));
        @endif
        @if(session('error'))
            document.addEventListener('DOMContentLoaded', () => showToast("{{ session('error') }}", 'error'));
        @endif
    </script>
    
    @stack('scripts')
</body>
</html>

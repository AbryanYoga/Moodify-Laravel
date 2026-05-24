<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mood->nama }} – Moodify Premium</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@900,700,500,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Premium Show CSS -->
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
</head>
<body>
    <!-- Animated background -->
    <div class="blur-bg"></div>

    <div class="container">
        <!-- Minimal Navbar -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <a href="/mood" style="color: var(--text-muted); text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; transition: color 0.3s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--text-muted)'">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
            
            <div style="font-family: 'Syne'; font-weight: 800; font-size: 1.2rem; background: linear-gradient(135deg, var(--accent), #fff); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Moodify
            </div>

            @auth
                <div style="display: flex; align-items: center; gap: 0.8rem;">
                    @if(auth()->user()->avatar)
                        <img src="{{ auth()->user()->avatar }}" alt="Avatar" style="width: 35px; height: 35px; border-radius: 50%; border: 2px solid var(--accent);">
                    @else
                        <div style="width: 35px; height: 35px; border-radius: 50%; background: var(--accent); display: flex; align-items: center; justify-content: center; font-weight: bold; color: #fff;">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    @endif
                </div>
            @else
                <a href="/auth/spotify" class="btn-primary" style="padding: 0.5rem 1.2rem; font-size: 0.85rem;">Login Spotify</a>
            @endauth
        </div>

        <!-- Hero Section -->
        <div class="hero-mood">
            @php
                $cleanImage = str_replace(['.jpg', "\r", "\n"], ['.png', '', ''], $mood->image);
            @endphp
            <img src="{{ asset('images/'.$cleanImage) }}" alt="{{ $mood->nama }}">
            <div class="hero-mood-info">
                <div class="badge-mood">Mood: {{ $mood->nama }}</div>
                <h1>{{ $mood->nama }}</h1>
                <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 500px; line-height: 1.6;">
                    Kumpulan rekomendasi lagu terbaik yang dikurasi secara otomatis berdasarkan genre <strong>{{ $mood->genre }}</strong> untuk menemani perasaanmu.
                </p>
            </div>
        </div>

        <!-- Tracks Section -->
        <div class="section-header">
            <h2 class="section-title">Playlist Rekomendasi</h2>
            <span id="track-count" style="color: var(--text-muted); font-weight: 600; font-size: 0.9rem;">Memuat...</span>
        </div>

        <!-- Hidden data for JS -->
        <div id="mood-data" data-id="{{ $mood->id }}" style="display: none;"></div>

        <!-- Dynamic Track Container -->
        <div id="track-container">
            <!-- JS akan merender konten di sini (termasuk skeleton loader saat awal muat) -->
        </div>

        <!-- Footer -->
        <div style="margin-top: 5rem; padding: 2rem 0; border-top: 1px solid var(--card-border); text-align: center; color: var(--text-muted); font-size: 0.9rem;">
            Moodify Premium &copy; {{ date('Y') }}
        </div>
    </div>

    <div id="toast-container" class="toast-container"></div>

    <!-- External JS -->
    <script src="{{ asset('js/show.js') }}"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mood->nama }} – Moodify</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Gunakan style utama agar konsisten (tidak jomplang) -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .hero-mood {
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 50px;
            background: var(--card);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: 30px;
            padding: 30px;
        }
        
        .hero-mood img {
            width: 200px;
            height: 200px;
            border-radius: 20px;
            object-fit: cover;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .hero-mood-info h1 {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 3.5rem;
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-mood-info .badge {
            background: rgba(178, 133, 255, 0.1);
            color: var(--accent);
            border: 1px solid rgba(178, 133, 255, 0.2);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 15px;
            display: inline-block;
        }

        .track-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .track-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 25px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            transition: 0.3s;
            backdrop-filter: blur(10px);
        }

        .track-row:hover {
            transform: translateY(-5px);
            border-color: var(--accent);
            box-shadow: 0 10px 25px rgba(178, 133, 255, 0.15);
        }

        .track-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .track-cover {
            width: 55px;
            height: 55px;
            border-radius: 10px;
            object-fit: cover;
        }

        .track-info h4 {
            margin: 0 0 5px 0;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text);
        }

        .track-info p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--muted);
        }

        .btn-play-track {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent) 0%, #8250e6 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(178, 133, 255, 0.4);
            font-size: 1.2rem;
        }

        .btn-play-track:hover {
            transform: scale(1.1);
            color: #fff;
            box-shadow: 0 8px 25px rgba(178, 133, 255, 0.6);
        }

        .btn-save {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: 0.3s;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-save:hover {
            background: rgba(255, 117, 195, 0.1);
            border-color: var(--accent2);
            color: var(--accent2);
        }

        .btn-save.saved {
            background: var(--accent2);
            border-color: var(--accent2);
            color: #fff;
            pointer-events: none;
        }

        .toast-container {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1050;
        }

        .toast-custom {
            background: var(--card);
            backdrop-filter: blur(12px);
            border: 1px solid var(--accent);
            color: var(--text);
            padding: 15px 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5), 0 0 20px rgba(178, 133, 255, 0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            transform: translateY(100px);
            opacity: 0;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .toast-custom.show {
            transform: translateY(0);
            opacity: 1;
        }

        .track-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .empty-state-card {
            background: var(--card);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: 30px;
            padding: 60px 20px;
            text-align: center;
        }

        @media(max-width: 768px) {
            .hero-mood {
                flex-direction: column;
                text-align: center;
            }
            .track-row {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
</head>

<body>
<div class="waveform-bg"></div>

<div class="container">
    <nav class="navbar">
        <a class="navbar-brand" href="/mood">
            ♬ Mood<span>ify</span>
        </a>
        <div class="nav-actions">
            <a href="/mood" class="btn-nav">
                ← Home
            </a>
            @auth
                <div class="user-box">
                    👤 {{ auth()->user()->name }}
                </div>
            @endauth
        </div>
    </nav>

    <div class="hero-mood">
        <img src="{{ asset('images/'.$mood->image) }}" alt="{{ $mood->nama }}">
        <div class="hero-mood-info">
            <div class="badge">Mood: {{ $mood->nama }}</div>
            <h1>{{ $mood->nama }}</h1>
            <p style="color: var(--muted); font-size: 1.1rem;">Berdasarkan genre {{ $mood->genre }}.</p>
        </div>
    </div>

    <div class="section-header">
        <h2 class="section-title">Rekomendasi Lagu</h2>
        @if(count($tracks) > 0)
            <span class="section-count">{{ count($tracks) }} lagu</span>
        @endif
    </div>

    @if(count($tracks) > 0)
        <div class="track-list">
            @foreach($tracks as $index => $track)
                <div class="track-row">
                    <div class="track-left">
                        @if(isset($track['album']['images'][2]['url']))
                            <img src="{{ $track['album']['images'][2]['url'] }}" alt="Cover" class="track-cover">
                        @else
                            <div class="track-cover" style="background:#333;"></div>
                        @endif
                        <div class="track-info">
                            <h4>{{ $track['name'] }}</h4>
                            <p>{{ collect($track['artists'])->pluck('name')->implode(', ') }} • {{ $track['album']['name'] }}</p>
                        </div>
                    </div>
                    <div class="track-right">
                        <a href="{{ $track['external_urls']['spotify'] }}" target="_blank" class="btn-play-track" title="Play di Spotify">
                            <i class="ph-fill ph-play"></i>
                        </a>
                        <button class="btn-save" data-track-id="{{ $track['id'] }}" onclick="saveTrack(this)">
                            <i class="ph ph-heart"></i> Save
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state-card">
            <div style="font-size: 4rem; color: var(--accent); margin-bottom: 20px;">
                <i class="ph-fill ph-warning-circle"></i>
            </div>
            <h3 style="font-family: 'Syne'; font-weight: 800;">Tidak ada lagu ditemukan</h3>
            @if(!auth()->check())
                <p style="color: var(--muted); margin-bottom: 30px;">Anda harus login ke Spotify untuk melihat rekomendasi.</p>
                <a href="/auth/spotify" class="btn-nav primary" style="font-size: 1.1rem; padding: 15px 30px;">
                    Login ke Spotify
                </a>
            @else
                <p style="color: var(--muted);">Maaf, kami tidak dapat menemukan lagu untuk mood ini saat ini.</p>
                <a href="/mood" class="btn-nav" style="margin-top: 20px;">Kembali ke Home</a>
            @endif
        </div>
    @endif
    
    <div class="footer" style="margin-top: 80px; padding: 40px 0; border-top: 1px solid var(--border); text-align: center; color: var(--muted);">
        Moodify · Musik untuk setiap perasaan
    </div>
</div>

<div id="toast-container" class="toast-container"></div>

<script>
    async function saveTrack(btn) {
        const trackId = btn.getAttribute('data-track-id');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        if (btn.classList.contains('saved') || btn.disabled) return;
        
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="ph ph-spinner ph-spin"></i> Saving...';
        btn.disabled = true;

        try {
            const response = await fetch('/spotify/save-track', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ track_id: trackId })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                btn.innerHTML = '<i class="ph-fill ph-heart"></i> Saved';
                btn.classList.add('saved');
                showToast(data.message || 'Lagu berhasil disimpan!', 'success');
            } else {
                btn.innerHTML = originalContent;
                btn.disabled = false;
                showToast(data.message || 'Gagal menyimpan lagu', 'error');
                
                if (response.status === 401) {
                    setTimeout(() => window.location.href = '/auth/spotify', 2000);
                }
            }
        } catch (error) {
            btn.innerHTML = originalContent;
            btn.disabled = false;
            showToast('Terjadi kesalahan jaringan', 'error');
        }
    }

    function showToast(message, type) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast-custom`;
        
        const icon = type === 'success' ? '<i class="ph-fill ph-check-circle" style="color: #1DB954; font-size: 1.5rem;"></i>' : '<i class="ph-fill ph-warning-circle" style="color: #ff5555; font-size: 1.5rem;"></i>';
            
        toast.innerHTML = `${icon} <span>${message}</span>`;
        container.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 400);
        }, 3000);
    }
</script>
</body>
</html>
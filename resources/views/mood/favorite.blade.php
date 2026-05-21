<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorit Saya — Moodify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/favorite.css') }}">
</head>
<body>

<div class="container">

    <nav class="navbar">
        <a class="navbar-brand" href="/mood">
            ♬ Mood<span>ify</span>
        </a>

        <div class="nav-actions">
            <button class="btn-icon" onclick="toggleMode()" title="Toggle theme">
                <span id="themeIcon">☀</span>
            </button>

            @auth
                <div class="user-box">
                    👤 {{ auth()->user()->name }}
                </div>

                <a href="/mood" class="btn-nav">
                    Home
                </a>

                <a href="/dashboard-mood" class="btn-nav primary">
                    Dashboard →
                </a>

                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn-nav logout-btn">
                        Logout
                    </button>
                </form>
            @else
                <a href="/auth/spotify" class="btn-nav green">
                    ⊕ Login Spotify
                </a>
            @endauth
        </div>
    </nav>

    <div class="page-header">
        <div class="page-eyebrow">
            <span>♥</span> Koleksi Musikku
        </div>
        <h1 class="page-title">
            Playlist<br><span class="highlight">Favorit Anda</span>
        </h1>
        <p class="page-sub">
            Temukan kembali lagu-lagu yang cocok dengan setiap getaran jiwa Anda. Tersimpan dengan aman di sini.
        </p>
        <div class="page-count">
            ♬ {{ count($favorites) }} playlist tersimpan
        </div>
    </div>

    @if(count($favorites) > 0)
        <div class="section-label">Saved Playlists</div>

        <div class="fav-list">
            @foreach ($favorites as $favorite)
            <div class="fav-item">
                <div class="fav-dot"></div>
                <span class="fav-name">{{ $favorite->playlist }}</span>
                <form action="/favorite/{{ $favorite->id }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-del">✕ Hapus</button>
                </form>
            </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">🎧</div>
            <h3>Belum ada playlist tersimpan</h3>
            <p>Jelajahi mood dan simpan musik yang paling menyentuh hati Anda untuk dinikmati kapan saja.</p>
        </div>
    @endif

    <a href="/mood" class="btn-back">
        ← Jelajahi Mood Lainnya
    </a>

    <div class="footer">
        Moodify · Musik untuk setiap perasaan
    </div>

</div>

<script src="{{ asset('js/script.js') }}"></script>

</body>
</html>

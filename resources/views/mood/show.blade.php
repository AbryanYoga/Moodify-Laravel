<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mood – Moodify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
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

            <a href="/mood" class="btn-nav">
                ← Home
            </a>

            @auth
                <div class="user-box">
                    👤 {{ auth()->user()->name }}
                </div>

                <a href="/favorite" class="btn-nav pink">
                    ♥ Favorite
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

    <div class="mood-hero">

        <img src="{{ asset('images/'.$mood->image) }}"
             alt="{{ $mood->nama }}">

        <div class="mood-hero-overlay"></div>

        <div class="mood-hero-content">

            <div class="mood-tag">
                ♬ {{ $mood->genre }}
            </div>

            <h1 class="mood-title">
                {{ $mood->nama }}
            </h1>

        </div>

    </div>

    <div class="section-label">
        Rekomendasi Playlist
    </div>

    <div class="playlist-list">

        @foreach ($playlists as $index => $playlist)

        <div class="playlist-item">

            <div class="playlist-info">
                <span class="playlist-num">
                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                </span>

                <span class="playlist-name">
                    {{ $playlist }}
                </span>

                <div class="equalizer">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </div>

            <form action="/favorite" method="POST">

                @csrf

                <input type="hidden"
                       name="playlist"
                       value="{{ $playlist }}">

                <button type="submit"
                        class="btn-fav">

                    ♥ Save

                </button>

            </form>

        </div>

        @endforeach

    </div>

    <a href="/mood"
       class="btn-back">

       ← Kembali

    </a>

    <div class="footer">
        Moodify · Music for every feeling
    </div>

</div>

<script src="{{ asset('js/script.js') }}"></script>
<script src="{{ asset('js/show.js') }}"></script>

</body>
</html>
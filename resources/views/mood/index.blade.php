<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moodify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<div class="container">

    <nav class="navbar">
        <a class="navbar-brand" href="/mood">
            ♬ Mood<span>ify</span>
        </a>

        <div class="nav-actions">

    <button class="btn-icon"
            onclick="toggleMode()"
            title="Toggle theme">

        <span id="themeIcon">☀</span>

    </button>

    @auth

        <div class="user-box">

            👤 {{ auth()->user()->name }}

        </div>

        <a href="/favorite"
           class="btn-nav pink">

            ♥ Favorit

        </a>

        <a href="/dashboard-mood"
           class="btn-nav primary">

            Dashboard →

        </a>

        <form action="/logout"
              method="POST">

            @csrf

            <button type="submit"
                    class="btn-nav logout-btn">

                Logout

            </button>

        </form>

    @else

        <a href="/auth/spotify"
           class="btn-nav green">

            ⊕ Login Spotify

        </a>

    @endauth

</div>
    </nav>

    <div class="hero">

        <div class="hero-eyebrow">
            <span>✦</span>
            Temukan musik sesuai suasana hatimu
        </div>

        <h1 class="hero-title">
            Rasakan <span class="highlight">Musiknya</span>
        </h1>

        <p class="hero-sub">
            Temukan playlist yang cocok dengan mood kamu.
            Setiap perasaan punya soundtracknya sendiri.
        </p>

    </div>

    <form action="/mood"
          method="GET"
          class="search-wrap">

        <div class="search-inner">

            <input type="text"
                   name="search"
                   placeholder="Cari mood — senang, santai, semangat…"
                   value="{{ $search ?? '' }}">

            <button type="submit">
                Cari
            </button>

        </div>

    </form>

    <div class="section-header">

        <h2 class="section-title">
            Semua Mood
        </h2>

        <span class="section-count">
            {{ count($moods) }} playlist
        </span>

    </div>

    <div class="mood-grid">

        @forelse ($moods as $mood)

        <a href="/mood/{{ $mood->id }}"
           class="mood-card">

            <div class="card-img-wrap">

                <img src="{{ asset('images/'.$mood->image) }}"
                     alt="{{ $mood->nama }}">

                <div class="card-img-overlay"></div>

            </div>

            <div class="card-body">

                <div class="card-tag">
                    ♬ {{ $mood->genre }}
                </div>

                <div class="card-name">
                    {{ $mood->nama }}
                </div>

            </div>

            <div class="card-arrow">
                →
            </div>

        </a>

        @empty

        <div class="empty-state">

            <div class="empty-icon">
                🔍
            </div>

            <h3>
                Mood tidak ditemukan
            </h3>

            <p>
                Coba cari dengan kata kunci yang berbeda.
            </p>

        </div>

        @endforelse

    </div>

    <div class="footer">
        Moodify · Musik untuk setiap perasaan
    </div>

</div>

<script src="{{ asset('js/script.js') }}"></script>

</body>
</html>
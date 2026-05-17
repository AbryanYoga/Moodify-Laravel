<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Mood – Moodify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
</head>

<body>

<div class="container">

    <nav class="navbar">

        <a class="navbar-brand" href="/mood">
            ♬ Mood<span>ify</span>
        </a>

        <div class="nav-actions">

            <a href="/mood" class="btn-nav">
                ← Home
            </a>

            <a href="/favorite" class="btn-nav pink">
                ♥ Favorite
            </a>

            <a href="/spotify/login" class="btn-nav green">
                ⊕ Spotify
            </a>

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

            <span class="playlist-num">
                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
            </span>

            <span class="playlist-name">
                {{ $playlist }}
            </span>

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

<script src="{{ asset('js/show.js') }}"></script>

</body>
</html>
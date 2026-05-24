<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favorite – Moodify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@900,700,500,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0a0a0f;
            --surface: #111118;
            --card: #16161f;
            --border: rgba(255,255,255,0.07);
            --text: #f0eff8;
            --muted: #7a7a9a;
            --accent: #c8b4fa;
            --accent2: #f4a8c7;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
        }

        body::before {
            content: '';
            position: fixed;
            top: -100px;
            right: -100px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(244,168,199,0.07) 0%, transparent 70%);
            pointer-events: none;
        }

        .container { max-width: 780px; margin: 0 auto; padding: 0 24px; position: relative; z-index: 1; }

        /* NAVBAR */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 0;
            border-bottom: 1px solid var(--border);
            margin-bottom: 52px;
        }

        .navbar-brand {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--text);
            text-decoration: none;
        }

        .navbar-brand span { color: var(--accent); }

        .nav-actions { display: flex; gap: 10px; }

        .btn-nav {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 18px;
            border-radius: 50px;
            font-size: 13.5px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            text-decoration: none;
            border: 1px solid var(--border);
            background: transparent;
            color: var(--muted);
            cursor: pointer;
            transition: all 0.25s;
        }

        .btn-nav:hover { background: rgba(255,255,255,0.06); color: var(--text); }
        .btn-nav.pink { background: rgba(244,168,199,0.1); color: var(--accent2); border-color: rgba(244,168,199,0.18); }
        .btn-nav.green { background: rgba(168,230,207,0.1); color: #a8e6cf; border-color: rgba(168,230,207,0.18); }
        .btn-nav.green:hover { background: rgba(168,230,207,0.18); }

        /* PAGE HEADER */
        .page-header {
            margin-bottom: 40px;
        }

        .page-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 50px;
            background: rgba(244,168,199,0.08);
            border: 1px solid rgba(244,168,199,0.15);
            color: var(--accent2);
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 18px;
        }

        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: clamp(32px, 5vw, 50px);
            font-weight: 800;
            letter-spacing: -1.5px;
            line-height: 1.1;
            color: var(--text);
            margin-bottom: 10px;
        }

        .page-sub {
            color: var(--muted);
            font-size: 15px;
        }

        .page-count {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 14px;
            font-size: 13px;
            color: var(--muted);
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 50px;
            padding: 5px 14px;
        }

        /* LIST */
        .section-label {
            font-family: 'Syne', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .fav-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 36px;
        }

        .fav-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            gap: 14px;
            transition: all 0.2s;
        }

        .fav-item:hover {
            border-color: rgba(255,255,255,0.12);
            background: #1c1c28;
        }

        .fav-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent2);
            flex-shrink: 0;
            opacity: 0.6;
        }

        .fav-name {
            flex: 1;
            font-size: 14.5px;
            font-weight: 500;
            color: var(--text);
        }

        .fav-item form { margin: 0; }

        .btn-del {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 50px;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--muted);
            font-size: 12px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-del:hover {
            background: rgba(242, 85, 90, 0.1);
            border-color: rgba(242, 85, 90, 0.3);
            color: #f2555a;
        }

        /* EMPTY STATE */
        .empty-state {
            text-align: center;
            padding: 80px 24px;
            border: 1px dashed var(--border);
            border-radius: 20px;
            margin-bottom: 36px;
        }

        .empty-icon {
            font-size: 48px;
            margin-bottom: 18px;
            opacity: 0.35;
        }

        .empty-state h3 {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
        }

        .empty-state p {
            color: var(--muted);
            font-size: 14.5px;
            line-height: 1.6;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 22px;
            border-radius: 50px;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            color: var(--muted);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-back:hover { background: rgba(255,255,255,0.09); color: var(--text); }

        .footer {
            margin-top: 80px;
            padding: 28px 0;
            border-top: 1px solid var(--border);
            text-align: center;
            color: var(--muted);
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="container">

    <nav class="navbar">
        <a class="navbar-brand" href="/mood">♬ Mood<span>ify</span></a>
        <div class="nav-actions">
            <a href="/mood" class="btn-nav">← Home</a>
            <a href="/favorite" class="btn-nav pink">♥ Favorite</a>
            <a href="/spotify/login" class="btn-nav green">⊕ Spotify</a>
        </div>
    </nav>

    <div class="page-header">
        <div class="page-eyebrow">♥ Koleksi Saya</div>
        <h1 class="page-title">Favorite<br>Playlist</h1>
        <p class="page-sub">Semua playlist yang kamu simpan ada di sini.</p>
        <div class="page-count">♬ {{ count($favorites) }} playlist tersimpan</div>
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
            <div class="empty-icon">♡</div>
            <h3>Belum ada playlist</h3>
            <p>Jelajahi mood dan tambahkan playlist<br>favoritmu ke koleksi.</p>
        </div>
    @endif

    <a href="/mood" class="btn-back">← Jelajahi Mood</a>

    <div class="footer">Moodify · Music for every feeling</div>

</div>

</body>
</html>
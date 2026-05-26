<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liked Songs - Moodify</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        :root {
            --bg-color: #09090b;
            --surface-color: rgba(24, 24, 27, 0.4);
            --surface-hover: rgba(39, 39, 42, 0.6);
            --text-primary: #ffffff;
            --text-secondary: #a1a1aa;
            --accent: #1db954;
            --gradient-1: #c026d3;
            --gradient-2: #db2777;
            --danger: #f43f5e;
            --border: rgba(255, 255, 255, 0.05);
            --glass-bg: rgba(9, 9, 11, 0.7);
            --font-main: 'Sora', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--font-main);
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Ambient Background */
        .ambient-bg {
            position: fixed;
            top: -20%;
            left: -10%;
            width: 70vw;
            height: 70vw;
            background: radial-gradient(circle, rgba(192, 38, 211, 0.15) 0%, rgba(219, 39, 119, 0.05) 40%, transparent 70%);
            filter: blur(100px);
            z-index: -1;
            pointer-events: none;
        }

        /* Hero Section */
        .hero {
            display: flex;
            align-items: flex-end;
            gap: 32px;
            padding: 4rem 2rem 3rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .hero-cover {
            width: 240px;
            height: 240px;
            background: linear-gradient(135deg, var(--gradient-1), var(--gradient-2));
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(192, 38, 211, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 6rem;
            color: white;
            position: relative;
        }
        
        .hero-cover::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent, rgba(0,0,0,0.2));
            border-radius: 16px;
        }

        .hero-cover i { position: relative; z-index: 2; }

        .hero-info {
            flex: 1;
        }

        .hero-label {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 12px;
            opacity: 0.9;
        }

        .hero-title {
            font-size: 5rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.04em;
            margin-bottom: 16px;
            text-shadow: 0 0 40px rgba(255,255,255,0.1);
        }

        .hero-meta {
            font-size: 1rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }
        .hero-meta .user { color: #fff; font-weight: 700; }

        /* Track List */
        .track-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem 4rem 2rem;
        }

        .track-header {
            display: grid;
            grid-template-columns: 48px minmax(250px, 2fr) 100px 80px;
            gap: 16px;
            padding: 12px 24px;
            border-bottom: 1px solid var(--border);
            color: var(--text-secondary);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-bottom: 16px;
        }

        .track-row {
            display: grid;
            grid-template-columns: 48px minmax(250px, 2fr) 100px 80px;
            gap: 16px;
            padding: 12px 24px;
            border-radius: 12px;
            align-items: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            background: transparent;
        }

        .track-row:hover {
            background: var(--surface-color);
            transform: scale(1.01);
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        }

        .track-index {
            color: var(--text-secondary);
            font-size: 1rem;
            font-variant-numeric: tabular-nums;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .track-row:hover .track-index span { display: none; }
        .track-row .play-icon { display: none; color: #fff; font-size: 1.25rem; }
        .track-row:hover .play-icon { display: inline-block; }

        .track-info-cell {
            display: flex;
            align-items: center;
            gap: 16px;
            min-width: 0;
        }

        .track-cover {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .track-text {
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .track-name {
            font-size: 1.05rem;
            color: #fff;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .track-artist {
            font-size: 0.85rem;
            color: var(--text-secondary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            transition: color 0.2s;
        }

        .track-row:hover .track-artist { color: #fff; }

        .track-duration {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-variant-numeric: tabular-nums;
            display: flex;
            align-items: center;
        }

        .track-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 16px;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .track-row:hover .track-actions { opacity: 1; }

        .btn-action {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-action:hover { color: #fff; transform: scale(1.1); }
        .btn-action.delete:hover { color: var(--danger); filter: drop-shadow(0 0 8px rgba(244, 63, 94, 0.4)); }

        .empty-state {
            text-align: center;
            padding: 6rem 2rem;
            background: var(--surface-color);
            border: 1px dashed var(--border);
            border-radius: 24px;
            margin-top: 2rem;
            backdrop-filter: blur(10px);
        }

        .empty-state i {
            font-size: 4rem;
            background: linear-gradient(135deg, var(--gradient-1), var(--gradient-2));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
        }

        .empty-state h3 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: var(--text-secondary);
            margin-bottom: 2rem;
            font-size: 1.05rem;
        }

        .btn-explore {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 14px 32px;
            background: #fff;
            color: #000;
            border-radius: 30px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
        }

        .btn-explore:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(255,255,255,0.2);
        }

        /* Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.8);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .modal-overlay.active { opacity: 1; visibility: visible; }

        .modal-content {
            background: #18181b;
            border: 1px solid var(--border);
            padding: 40px;
            border-radius: 24px;
            width: 90%;
            max-width: 420px;
            text-align: center;
            transform: scale(0.95);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .modal-overlay.active .modal-content { transform: scale(1); }

        .modal-icon {
            width: 72px; height: 72px;
            border-radius: 50%;
            background: rgba(244, 63, 94, 0.1);
            color: var(--danger);
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 20px;
        }

        .modal-btn-group {
            display: flex; gap: 16px; margin-top: 32px;
        }

        .btn-cancel, .btn-confirm {
            flex: 1; padding: 14px; border-radius: 30px;
            font-weight: 700; cursor: pointer; transition: 0.2s;
            font-size: 1rem;
        }
        .btn-cancel {
            background: transparent; color: #fff;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .btn-cancel:hover { border-color: #fff; background: rgba(255,255,255,0.05); }

        .btn-confirm {
            background: var(--danger); color: #fff;
            border: none;
        }
        .btn-confirm:hover { background: #e11d48; transform: scale(1.02); box-shadow: 0 8px 20px rgba(244, 63, 94, 0.3); }

        @media (max-width: 768px) {
            .hero { 
                flex-direction: column; 
                align-items: center; 
                text-align: center; 
                padding: 2rem 1rem; 
                gap: 24px;
            }
            .hero-cover { 
                width: 180px; 
                height: 180px; 
                font-size: 4rem; 
            }
            .hero-title { 
                font-size: 2.5rem; 
            }
            .hero-meta { 
                justify-content: center; 
                flex-wrap: wrap;
            }
            .track-container {
                padding: 0 1rem 3rem 1rem;
            }
            .track-header { 
                display: none; 
            }
            .track-row { 
                grid-template-columns: 30px 1fr 60px; 
                padding: 12px 16px; 
            }
            .track-duration { 
                display: none; 
            }
            .track-actions { 
                opacity: 1; 
            }
            .empty-state {
                padding: 4rem 1.5rem;
                margin-top: 1rem;
            }
            .empty-state i {
                font-size: 3rem;
            }
            .empty-state h3 {
                font-size: 1.5rem;
            }
            .empty-state p {
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .hero {
                padding: 1.5rem 0.75rem;
                gap: 20px;
            }
            .hero-cover {
                width: 150px;
                height: 150px;
                font-size: 3.5rem;
                border-radius: 12px;
            }
            .hero-label {
                font-size: 0.75rem;
                margin-bottom: 8px;
            }
            .hero-title {
                font-size: 2rem;
                margin-bottom: 12px;
            }
            .hero-meta {
                font-size: 0.9rem;
            }
            .track-container {
                padding: 0 0.75rem 2rem 0.75rem;
            }
            .track-row {
                grid-template-columns: 28px 1fr 50px;
                padding: 10px 12px;
                gap: 12px;
            }
            .track-index {
                font-size: 0.9rem;
            }
            .track-cover {
                width: 44px;
                height: 44px;
                border-radius: 6px;
            }
            .track-info-cell {
                gap: 12px;
            }
            .track-name {
                font-size: 0.95rem;
            }
            .track-artist {
                font-size: 0.8rem;
            }
            .track-actions {
                gap: 12px;
            }
            .btn-action {
                font-size: 1.1rem;
            }
            .empty-state {
                padding: 3rem 1rem;
                border-radius: 16px;
            }
            .empty-state i {
                font-size: 2.5rem;
                margin-bottom: 1rem;
            }
            .empty-state h3 {
                font-size: 1.3rem;
                margin-bottom: 0.4rem;
            }
            .empty-state p {
                font-size: 0.9rem;
                margin-bottom: 1.5rem;
            }
            .btn-explore {
                padding: 12px 28px;
                font-size: 0.95rem;
            }
            .modal-content {
                padding: 32px 24px;
                max-width: 90%;
            }
            .modal-icon {
                width: 64px;
                height: 64px;
                font-size: 2rem;
                margin-bottom: 16px;
            }
            .modal-content h3 {
                font-size: 1.3rem;
            }
            .modal-content p {
                font-size: 0.9rem;
            }
            .modal-btn-group {
                flex-direction: column;
                gap: 12px;
                margin-top: 24px;
            }
            .btn-cancel, .btn-confirm {
                padding: 12px;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>

    <div class="ambient-bg"></div>

    @include('layouts.navigation')

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-cover">
            <i class="ph-fill ph-heart"></i>
        </div>
        <div class="hero-info">
            <div class="hero-label">Playlist</div>
            <h1 class="hero-title">Liked Songs</h1>
            <div class="hero-meta">
                <div class="user-avatar" style="width: 24px; height: 24px; font-size: 0.6rem;">{{ substr(Auth::user()->name, 0, 1) }}</div>
                <span class="user">{{ Auth::user()->name }}</span>
                <span>•</span>
                <span>{{ $favorites->count() }} songs</span>
            </div>
        </div>
    </div>

    <!-- Track List -->
    <div class="track-container">
        @if(session('success'))
            <div style="background: rgba(29, 185, 84, 0.1); border: 1px solid rgba(29, 185, 84, 0.3); color: #1db954; padding: 16px; border-radius: 12px; margin-bottom: 24px; display: flex; align-items: center; gap: 12px; font-weight: 500;">
                <i class="ph-fill ph-check-circle" style="font-size: 1.25rem;"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($favorites->isEmpty())
            <div class="empty-state">
                <i class="ph-fill ph-music-notes-simple"></i>
                <h3>Songs you like will appear here</h3>
                <p>Save songs by tapping the heart icon in the music player.</p>
                <a href="{{ route('dashboard') }}" class="btn-explore">
                    Find songs
                </a>
            </div>
        @else
            <div class="track-header">
                <div style="text-align: center;">#</div>
                <div>Title</div>
                <div>Date Added</div>
                <div></div>
            </div>

            <div class="track-list">
                @foreach($favorites as $index => $fav)
                    <div class="track-row">
                        <div class="track-index">
                            <span>{{ $index + 1 }}</span>
                            @if($fav->spotify_url)
                                <a href="{{ $fav->spotify_url }}" target="_blank" class="play-icon" title="Play on Spotify">
                                    <i class="ph-fill ph-play"></i>
                                </a>
                            @else
                                <div class="play-icon"><i class="ph-fill ph-play"></i></div>
                            @endif
                        </div>
                        
                        <div class="track-info-cell">
                            <img src="{{ $fav->album_image ?? asset('images/default-album.png') }}" alt="Cover" class="track-cover">
                            <div class="track-text">
                                <div class="track-name" title="{{ $fav->track_name }}">{{ $fav->track_name }}</div>
                                <div class="track-artist" title="{{ $fav->artist_name }}">{{ $fav->artist_name }}</div>
                            </div>
                        </div>

                        <div class="track-duration">
                            {{ $fav->created_at->format('M d, Y') }}
                        </div>

                        <div class="track-actions">
                            @if($fav->spotify_url)
                                <a href="{{ $fav->spotify_url }}" target="_blank" class="btn-action" title="Open in Spotify">
                                    <i class="ph ph-arrow-square-out"></i>
                                </a>
                            @endif
                            <button type="button" class="btn-action delete" onclick="confirmDelete({{ $fav->id }}, '{{ addslashes($fav->track_name) }}')" title="Remove from your Liked Songs">
                                <i class="ph-fill ph-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="ph-fill ph-warning"></i>
            </div>
            <h3 style="color: #fff; font-size: 1.5rem; font-weight: 700; margin-bottom: 12px;">Remove Song?</h3>
            <p style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.5;">Are you sure you want to remove "<span id="deleteTrackName" style="color: #fff; font-weight: 600;"></span>" from your Liked Songs?</p>
            
            <form id="deleteForm" method="POST" class="modal-btn-group">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-confirm">Remove</button>
            </form>
        </div>
    </div>

    <script>
        function confirmDelete(id, name) {
            document.getElementById('deleteTrackName').textContent = name;
            document.getElementById('deleteForm').action = `/favorite/${id}`;
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>

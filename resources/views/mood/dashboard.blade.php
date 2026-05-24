<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Moodify</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@900,700,500,400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Gunakan style.css utama -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .stat-card {
            background: var(--card);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: 30px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            transition: 0.4s;
            height: 100%;
        }
        
        .stat-card:hover {
            border-color: var(--accent);
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.4), 0 0 20px rgba(178, 133, 255, 0.1);
        }

        .stat-card h5 {
            color: var(--muted);
            font-family: 'DM Sans', sans-serif;
            font-size: 1.1rem;
            margin-bottom: 15px;
        }

        .stat-card h1 {
            font-family: 'Syne', sans-serif;
            font-size: 3.5rem;
            font-weight: 800;
            margin: 0;
            background: linear-gradient(135deg, var(--text) 0%, var(--muted) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .chart-card {
            background: var(--card);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: 30px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .dashboard-title {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 30px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .recent-fav-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border);
            border-radius: 15px;
            margin-bottom: 10px;
            transition: 0.3s;
        }

        .recent-fav-item:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--accent);
            transform: translateX(5px);
        }
    </style>
</head>

<body>
<div class="waveform-bg"></div>

<div class="container py-4">
    
    <nav class="navbar" style="margin-top: 0; margin-bottom: 40px;">
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

    <h1 class="dashboard-title">Overview Dashboard</h1>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="stat-card">
                <h5>Total Mood</h5>
                <h1>{{ $totalMood }}</h1>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <h5>Total Favorite</h5>
                <h1>{{ $totalFavorite }}</h1>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="chart-card">
                <h4 style="font-family: 'Syne'; font-weight: 700; margin-bottom: 25px;">Statistik Genre Mood</h4>
                <div style="position: relative; height: 350px; width: 100%;">
                    <canvas id="genreChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="chart-card" style="height: 100%;">
                <h4 style="font-family: 'Syne'; font-weight: 700; margin-bottom: 25px;">Favorite Terbaru</h4>
                @forelse($latestFavorites as $fav)
                    <div class="recent-fav-item">
                        <span style="font-size: 1.2rem;">🎵</span>
                        <span style="font-size: 1.05rem; font-weight: 500;">{{ $fav->playlist }}</span>
                    </div>
                @empty
                    <p style="color: var(--muted);">Belum ada favorite ditambahkan.</p>
                @endforelse
            </div>
        </div>
    </div>

</div>

<script>
    Chart.defaults.color = '#8b8ba7';
    Chart.defaults.font.family = "'DM Sans', sans-serif";

    const ctx = document.getElementById('genreChart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                @foreach($genres as $genre)
                    '{{ ucfirst($genre->genre) }}',
                @endforeach
            ],
            datasets: [{
                label: 'Total',
                data: [
                    @foreach($genres as $genre)
                        {{ $genre->total }},
                    @endforeach
                ],
                backgroundColor: 'rgba(178, 133, 255, 0.8)',
                borderColor: 'rgba(178, 133, 255, 1)',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false }
                },
                x: {
                    grid: { display: false, drawBorder: false }
                }
            }
        }
    });
</script>

</body>
</html>
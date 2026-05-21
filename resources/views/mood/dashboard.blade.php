<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard Moodify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body{
            background:#030305;
            color:#f0eff8;
            font-family:'DM Sans', sans-serif;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: -20%; left: -20%;
            width: 50vw; height: 50vw;
            background: radial-gradient(circle, rgba(178, 133, 255, 0.2) 0%, transparent 70%);
            z-index: -1; filter: blur(80px);
        }

        .card-dark{
            background: rgba(22, 22, 31, 0.45);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            transition: 0.4s;
        }
        .card-dark:hover {
            border-color: rgba(178, 133, 255, 0.4);
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.4), 0 0 30px rgba(178, 133, 255, 0.1);
        }
        
        /* Light Mode for Dashboard */
        html.light body {
            background: #f4f3f8;
            color: #18171f;
        }
        html.light body::before {
            background: radial-gradient(circle, rgba(124, 92, 191, 0.1) 0%, transparent 70%);
        }
        html.light .card-dark {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }
        html.light .card-dark:hover {
            border-color: rgba(124, 92, 191, 0.3);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08), 0 0 20px rgba(124, 92, 191, 0.1);
        }

        h1, h4, h5 {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
        }
        .text-gradient {
            background: linear-gradient(135deg, #b285ff, #ff75c3);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>

</head>

<body>

<div class="container py-5">

    <h1 class="mb-5 fw-bold text-gradient">
        📊 Dashboard Moodify
    </h1>

    <div class="row mb-4">

        <div class="col-md-6">

            <div class="card card-dark p-4">

                <h5>Total Mood</h5>

                <h1>{{ $totalMood }}</h1>

            </div>

        </div>

        <div class="col-md-6">

            <div class="card card-dark p-4">

                <h5>Total Favorite</h5>

                <h1>{{ $totalFavorite }}</h1>

            </div>

        </div>

    </div>

    <div class="card card-dark p-4 mb-4">

        <h4 class="mb-4">
            Genre Mood
        </h4>

        <canvas id="genreChart"></canvas>

    </div>

    <div class="card card-dark p-4">

        <h4 class="mb-4">
            Favorite Terbaru
        </h4>

        @foreach($latestFavorites as $fav)

            <div class="mb-3">
                🎵 {{ $fav->playlist }}
            </div>

        @endforeach

    </div>

</div>

<script>

const ctx = document.getElementById('genreChart');

new Chart(ctx, {

    type: 'bar',

    data: {

        labels: [
            @foreach($genres as $genre)
                '{{ $genre->genre }}',
            @endforeach
        ],

        datasets: [{
            label: 'Total Genre',

            data: [
                @foreach($genres as $genre)
                    {{ $genre->total }},
                @endforeach
            ],

            borderWidth: 1
        }]
    }
});

</script>

<script src="{{ asset('js/script.js') }}"></script>

</body>
</html>
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

    <style>

        body{
            background:#0f0f14;
            color:white;
            font-family:sans-serif;
        }

        .card-dark{
            background:#1a1a24;
            border:none;
            border-radius:20px;
        }

    </style>

</head>

<body>

<div class="container py-5">

    <h1 class="mb-5 fw-bold">
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

</body>
</html>
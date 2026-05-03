<!DOCTYPE html>
<html>
<head>
    <title>Pilih Mood</title>
</head>
<body>

<h1>Pilih Mood Kamu</h1>

@foreach ($moods as $mood)
    <div style="margin:10px; padding:10px; border:1px
solid #000;">
        <a href="{{ route('mood.show', $mood->id) }}">
        {{  $mood->nama }}
        </a>
    </div>
@endforeach
    
</body>
</html>
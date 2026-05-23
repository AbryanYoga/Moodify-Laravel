<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$moods = App\Models\Mood::all();
foreach ($moods as $mood) {
    $mood->genre = trim($mood->genre);
    if ($mood->genre === 'sad pop') {
        $mood->genre = 'sad';
    }
    $mood->save();
}
echo "DB Fixed\n";

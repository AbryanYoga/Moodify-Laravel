<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::first();
if (!$user) {
    echo "No user found\n";
    exit;
}
$token = $user->spotify_token;
if (!$token) {
    echo "No spotify_token for user\n";
    exit;
}

$query = urlencode('genre:pop');
$response = \Illuminate\Support\Facades\Http::withToken($token)
    ->get("https://api.spotify.com/v1/search?q={$query}&type=track&limit=12");

echo "STATUS: " . $response->status() . "\n";
echo "BODY: " . $response->body() . "\n";

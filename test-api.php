<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

foreach(User::all() as $user) {
    echo "ID: " . $user->id . " Name: " . $user->name . " Token: " . substr($user->spotify_token, 0, 10) . "...\n";
}

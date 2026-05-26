<?php
/**
 * Spotify Configuration Test Script
 * 
 * Jalankan script ini untuk memverifikasi konfigurasi Spotify OAuth
 * 
 * Usage: php test-spotify-config.php
 */

echo "🎵 Spotify OAuth Configuration Test\n";
echo "=====================================\n\n";

// Load .env file
if (!file_exists(__DIR__ . '/.env')) {
    echo "❌ ERROR: File .env tidak ditemukan!\n";
    echo "   Silakan copy .env.example ke .env dan isi konfigurasi Spotify.\n";
    exit(1);
}

$envContent = file_get_contents(__DIR__ . '/.env');
$envLines = explode("\n", $envContent);

$config = [];
foreach ($envLines as $line) {
    $line = trim($line);
    if (empty($line) || strpos($line, '#') === 0) {
        continue;
    }
    
    $parts = explode('=', $line, 2);
    if (count($parts) === 2) {
        $key = trim($parts[0]);
        $value = trim($parts[1]);
        $config[$key] = $value;
    }
}

// Check required config
$required = [
    'SPOTIFY_CLIENT_ID',
    'SPOTIFY_CLIENT_SECRET',
    'SPOTIFY_REDIRECT_URI'
];

$allConfigured = true;

echo "📋 Checking Configuration:\n";
echo "----------------------------\n";

foreach ($required as $key) {
    $value = $config[$key] ?? '';
    $isEmpty = empty($value);
    
    $status = $isEmpty ? '❌' : '✅';
    $displayValue = $isEmpty ? '(not set)' : substr($value, 0, 20) . '...';
    
    echo "$status $key: $displayValue\n";
    
    if ($isEmpty) {
        $allConfigured = false;
    }
}

echo "\n";

if (!$allConfigured) {
    echo "❌ KONFIGURASI TIDAK LENGKAP!\n\n";
    echo "Langkah-langkah:\n";
    echo "1. Buka https://developer.spotify.com/dashboard\n";
    echo "2. Buat app baru atau pilih app yang sudah ada\n";
    echo "3. Copy Client ID dan Client Secret\n";
    echo "4. Tambahkan ke file .env:\n\n";
    echo "   SPOTIFY_CLIENT_ID=your_client_id_here\n";
    echo "   SPOTIFY_CLIENT_SECRET=your_client_secret_here\n";
    echo "   SPOTIFY_REDIRECT_URI=http://localhost:8000/auth/spotify/callback\n\n";
    echo "5. Pastikan Redirect URI di Spotify Dashboard sama dengan di .env\n";
    echo "6. Jalankan: php artisan config:clear\n\n";
    exit(1);
}

// Validate redirect URI format
$redirectUri = $config['SPOTIFY_REDIRECT_URI'];
if (!filter_var($redirectUri, FILTER_VALIDATE_URL)) {
    echo "❌ REDIRECT URI tidak valid!\n";
    echo "   Format harus: http://localhost:8000/auth/spotify/callback\n";
    echo "   Saat ini: $redirectUri\n\n";
    exit(1);
}

echo "✅ KONFIGURASI LENGKAP!\n\n";

// Generate authorization URL
$clientId = $config['SPOTIFY_CLIENT_ID'];
$redirectUri = urlencode($config['SPOTIFY_REDIRECT_URI']);
$scopes = urlencode('user-read-email user-read-private');
$state = bin2hex(random_bytes(16));

$authUrl = "https://accounts.spotify.com/authorize?" . http_build_query([
    'client_id' => $clientId,
    'response_type' => 'code',
    'redirect_uri' => $config['SPOTIFY_REDIRECT_URI'],
    'scope' => 'user-read-email user-read-private',
    'state' => $state,
    'show_dialog' => 'true'
]);

echo "🔗 Test Authorization URL:\n";
echo "----------------------------\n";
echo "$authUrl\n\n";

echo "📝 Langkah Testing:\n";
echo "----------------------------\n";
echo "1. Copy URL di atas\n";
echo "2. Paste di browser\n";
echo "3. Anda akan diarahkan ke halaman Spotify\n";
echo "4. Halaman akan menampilkan:\n";
echo "   - Nama app: Moodify (atau nama app Anda)\n";
echo "   - Permissions yang diminta\n";
echo "   - Tombol 'Agree' atau 'Cancel'\n";
echo "5. Klik 'Agree'\n";
echo "6. Anda akan di-redirect ke: {$config['SPOTIFY_REDIRECT_URI']}\n\n";

echo "✅ Jika muncul halaman authorize Spotify dengan tombol 'Agree',\n";
echo "   berarti konfigurasi sudah BENAR!\n\n";

echo "❌ Jika muncul error 'Invalid redirect URI' atau 'Invalid client',\n";
echo "   berarti ada yang salah dengan konfigurasi.\n\n";

echo "📚 Baca SPOTIFY_SETUP.md untuk panduan lengkap!\n\n";

echo "🎉 Happy Coding!\n";

<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('spotify:test')]
#[Description('Test Spotify OAuth configuration')]
class TestSpotifyConfig extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎵 Spotify OAuth Configuration Test');
        $this->info('=====================================');
        $this->newLine();

        // Check if config values are set
        $clientId = config('services.spotify.client_id');
        $clientSecret = config('services.spotify.client_secret');
        $redirectUri = config('services.spotify.redirect');

        $this->info('📋 Checking Configuration:');
        $this->line('----------------------------');

        $hasError = false;

        // Check Client ID
        if (empty($clientId)) {
            $this->error('❌ SPOTIFY_CLIENT_ID: Not set');
            $hasError = true;
        } else {
            $this->info('✅ SPOTIFY_CLIENT_ID: ' . substr($clientId, 0, 20) . '...');
        }

        // Check Client Secret
        if (empty($clientSecret)) {
            $this->error('❌ SPOTIFY_CLIENT_SECRET: Not set');
            $hasError = true;
        } else {
            $this->info('✅ SPOTIFY_CLIENT_SECRET: ' . substr($clientSecret, 0, 20) . '...');
        }

        // Check Redirect URI
        if (empty($redirectUri)) {
            $this->error('❌ SPOTIFY_REDIRECT_URI: Not set');
            $hasError = true;
        } else {
            $this->info('✅ SPOTIFY_REDIRECT_URI: ' . $redirectUri);
        }

        $this->newLine();

        if ($hasError) {
            $this->error('❌ KONFIGURASI TIDAK LENGKAP!');
            $this->newLine();
            $this->line('Langkah-langkah:');
            $this->line('1. Buka https://developer.spotify.com/dashboard');
            $this->line('2. Buat app baru atau pilih app yang sudah ada');
            $this->line('3. Copy Client ID dan Client Secret');
            $this->line('4. Tambahkan ke file .env:');
            $this->newLine();
            $this->line('   SPOTIFY_CLIENT_ID=your_client_id_here');
            $this->line('   SPOTIFY_CLIENT_SECRET=your_client_secret_here');
            $this->line('   SPOTIFY_REDIRECT_URI=http://localhost:8000/auth/spotify/callback');
            $this->newLine();
            $this->line('5. Jalankan: php artisan config:clear');
            $this->newLine();
            return 1;
        }

        // Validate redirect URI
        if (!filter_var($redirectUri, FILTER_VALIDATE_URL)) {
            $this->error('❌ REDIRECT URI tidak valid!');
            $this->line('   Format harus: http://localhost:8000/auth/spotify/callback');
            $this->line('   Saat ini: ' . $redirectUri);
            $this->newLine();
            return 1;
        }

        $this->info('✅ KONFIGURASI LENGKAP!');
        $this->newLine();

        // Generate test authorization URL
        $state = bin2hex(random_bytes(16));
        $authUrl = 'https://accounts.spotify.com/authorize?' . http_build_query([
            'client_id' => $clientId,
            'response_type' => 'code',
            'redirect_uri' => $redirectUri,
            'scope' => 'user-read-email user-read-private',
            'state' => $state,
            'show_dialog' => 'true'
        ]);

        $this->info('🔗 Test Authorization URL:');
        $this->line('----------------------------');
        $this->line($authUrl);
        $this->newLine();

        $this->info('📝 Langkah Testing:');
        $this->line('----------------------------');
        $this->line('1. Copy URL di atas');
        $this->line('2. Paste di browser');
        $this->line('3. Anda akan diarahkan ke halaman Spotify');
        $this->line('4. Halaman akan menampilkan:');
        $this->line('   - Nama app: Moodify (atau nama app Anda)');
        $this->line('   - Permissions yang diminta');
        $this->line('   - Tombol \'Agree\' atau \'Cancel\'');
        $this->line('5. Klik \'Agree\'');
        $this->line('6. Anda akan di-redirect ke: ' . $redirectUri);
        $this->newLine();

        $this->info('✅ Jika muncul halaman authorize Spotify dengan tombol \'Agree\',');
        $this->info('   berarti konfigurasi sudah BENAR!');
        $this->newLine();

        $this->error('❌ Jika muncul error \'Invalid redirect URI\' atau \'Invalid client\',');
        $this->error('   berarti ada yang salah dengan konfigurasi.');
        $this->newLine();

        $this->info('📚 Baca SPOTIFY_SETUP.md untuk panduan lengkap!');
        $this->newLine();

        $this->info('🎉 Happy Coding!');

        return 0;
    }
}

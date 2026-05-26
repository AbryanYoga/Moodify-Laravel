# 🎵 Spotify OAuth Setup Guide

Panduan lengkap untuk mengintegrasikan Spotify OAuth ke aplikasi Moodify agar user diarahkan ke halaman authorize resmi Spotify.

---

## 📋 Prerequisites

1. Akun Spotify (gratis atau premium)
2. Aplikasi Laravel sudah running
3. Composer sudah terinstall

---

## 🚀 Step-by-Step Setup

### 1️⃣ **Buat Spotify App di Spotify Developer Dashboard**

1. Buka [Spotify Developer Dashboard](https://developer.spotify.com/dashboard)
2. Login dengan akun Spotify Anda
3. Klik **"Create app"**
4. Isi form:
   - **App name**: `Moodify` (atau nama aplikasi Anda)
   - **App description**: `Music mood discovery app`
   - **Website**: `http://localhost:8000` (untuk development)
   - **Redirect URIs**: `http://localhost:8000/auth/spotify/callback`
   - **API/SDKs**: Centang **Web API**
5. Centang agreement dan klik **"Save"**

### 2️⃣ **Dapatkan Client ID dan Client Secret**

1. Setelah app dibuat, klik app Anda di dashboard
2. Klik **"Settings"** di kanan atas
3. Copy **Client ID**
4. Klik **"View client secret"** dan copy **Client Secret**
5. **PENTING**: Jangan share Client Secret ke publik!

### 3️⃣ **Konfigurasi .env File**

1. Buka file `.env` di root project
2. Tambahkan atau update konfigurasi Spotify:

```env
SPOTIFY_CLIENT_ID=your_client_id_here
SPOTIFY_CLIENT_SECRET=your_client_secret_here
SPOTIFY_REDIRECT_URI=http://localhost:8000/auth/spotify/callback
```

**Contoh:**
```env
SPOTIFY_CLIENT_ID=abc123def456ghi789jkl012mno345pq
SPOTIFY_CLIENT_SECRET=xyz789uvw456rst123opq890lmn567hij
SPOTIFY_REDIRECT_URI=http://localhost:8000/auth/spotify/callback
```

### 4️⃣ **Clear Config Cache**

Setelah update `.env`, jalankan command:

```bash
php artisan config:clear
php artisan cache:clear
```

### 5️⃣ **Install Laravel Socialite (Jika Belum)**

Jika belum install Socialite, jalankan:

```bash
composer require laravel/socialite
composer require socialiteproviders/spotify
```

### 6️⃣ **Verifikasi Konfigurasi**

Pastikan file `config/services.php` sudah ada konfigurasi Spotify:

```php
'spotify' => [
    'client_id' => env('SPOTIFY_CLIENT_ID'),
    'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
    'redirect' => env('SPOTIFY_REDIRECT_URI'),
],
```

---

## 🧪 Testing OAuth Flow

### **Flow yang Benar:**

1. User klik tombol **"⊕ Login Spotify"** di navbar
2. User diarahkan ke **halaman authorize Spotify** (accounts.spotify.com)
3. Halaman menampilkan:
   - Nama aplikasi: **Moodify**
   - Permissions yang diminta:
     - ✅ Read your email address
     - ✅ Access your profile information
   - Tombol **"Agree"** atau **"Cancel"**
4. User klik **"Agree"**
5. Spotify redirect ke: `http://localhost:8000/auth/spotify/callback?code=...`
6. Aplikasi menerima authorization code
7. Aplikasi menukar code dengan access token
8. User login berhasil dan redirect ke `/mood`

### **Test Login:**

1. Jalankan aplikasi: `php artisan serve`
2. Buka browser: `http://localhost:8000/mood`
3. Klik tombol **"⊕ Login Spotify"**
4. Anda akan diarahkan ke URL seperti:
   ```
   https://accounts.spotify.com/authorize?
   client_id=YOUR_CLIENT_ID&
   redirect_uri=http://localhost:8000/auth/spotify/callback&
   scope=user-read-email+user-read-private&
   response_type=code&
   state=RANDOM_STATE
   ```
5. Halaman Spotify akan muncul dengan tombol **"Agree"**

---

## 🔧 Troubleshooting

### ❌ **Error: "Invalid redirect URI"**

**Penyebab:** Redirect URI di `.env` tidak match dengan yang di Spotify Dashboard

**Solusi:**
1. Buka Spotify Developer Dashboard
2. Masuk ke Settings app Anda
3. Pastikan **Redirect URIs** berisi: `http://localhost:8000/auth/spotify/callback`
4. Klik **"Save"**
5. Clear cache: `php artisan config:clear`

### ❌ **Error: "Invalid client"**

**Penyebab:** Client ID atau Client Secret salah

**Solusi:**
1. Cek kembali Client ID dan Secret di Spotify Dashboard
2. Copy ulang ke `.env`
3. Pastikan tidak ada spasi atau karakter tambahan
4. Clear cache: `php artisan config:clear`

### ❌ **Tidak redirect ke halaman authorize Spotify**

**Penyebab:** Socialite belum terinstall atau konfigurasi salah

**Solusi:**
1. Install Socialite: `composer require laravel/socialite`
2. Install Spotify Provider: `composer require socialiteproviders/spotify`
3. Pastikan route `/auth/spotify` ada di `routes/web.php`
4. Clear cache: `php artisan route:clear`

### ❌ **Error: "CSRF token mismatch"**

**Penyebab:** Session tidak tersimpan dengan benar

**Solusi:**
1. Pastikan `SESSION_DRIVER=database` di `.env`
2. Jalankan migration: `php artisan migrate`
3. Clear cache: `php artisan cache:clear`

---

## 🔐 Security Best Practices

1. **Jangan commit `.env` ke Git**
   - File `.env` sudah ada di `.gitignore`
   - Gunakan `.env.example` sebagai template

2. **Gunakan HTTPS di Production**
   - Update `SPOTIFY_REDIRECT_URI` ke `https://yourdomain.com/auth/spotify/callback`
   - Update di Spotify Dashboard juga

3. **Rotate Client Secret secara berkala**
   - Bisa dilakukan di Spotify Dashboard > Settings

4. **Limit Scopes**
   - Hanya minta permission yang benar-benar dibutuhkan
   - Saat ini: `user-read-email`, `user-read-private`

---

## 📱 Production Deployment

Ketika deploy ke production (misalnya ke domain `moodify.com`):

### 1. Update Spotify Dashboard:
- **Website**: `https://moodify.com`
- **Redirect URIs**: `https://moodify.com/auth/spotify/callback`

### 2. Update `.env` Production:
```env
APP_URL=https://moodify.com
SPOTIFY_REDIRECT_URI=https://moodify.com/auth/spotify/callback
```

### 3. Clear Cache di Server:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

---

## 🎯 Expected Behavior

### ✅ **Correct Flow:**

```
User clicks "Login Spotify"
    ↓
Redirect to: https://accounts.spotify.com/authorize
    ↓
Spotify shows authorization page with:
    - App name: Moodify
    - Permissions requested
    - "Agree" button
    ↓
User clicks "Agree"
    ↓
Redirect to: http://localhost:8000/auth/spotify/callback?code=...
    ↓
App exchanges code for access token
    ↓
User logged in successfully
    ↓
Redirect to: /mood
```

### ❌ **Incorrect Flow (if misconfigured):**

```
User clicks "Login Spotify"
    ↓
Error: "Invalid redirect URI"
OR
Error: "Invalid client"
OR
Stuck on loading page
```

---

## 📞 Support

Jika masih ada masalah:

1. **Check Laravel Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Check Browser Console:**
   - Press F12
   - Check Console tab for errors

3. **Verify Routes:**
   ```bash
   php artisan route:list --path=auth
   ```

4. **Test Spotify API:**
   - Buka: https://developer.spotify.com/console/
   - Test API endpoints

---

## 🎉 Success!

Jika setup berhasil, Anda akan melihat:
- ✅ Halaman authorize Spotify muncul
- ✅ Tombol "Agree" terlihat
- ✅ Setelah agree, user login berhasil
- ✅ User data tersimpan di database
- ✅ User bisa akses fitur yang memerlukan Spotify token

**Happy Coding! 🚀**

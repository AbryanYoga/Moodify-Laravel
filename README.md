# ♬ Moodify

Moodify adalah website rekomendasi musik berbasis mood yang dibuat menggunakan Laravel dan Spotify API.
User dapat memilih suasana hati seperti senang, galau, santai, fokus, dan mendapatkan rekomendasi lagu yang sesuai.

---

# ✨ Features

* 🎵 Mood-based music recommendation
* 🔐 Login with Spotify
* ❤️ Save favorite songs
* 📂 Favorite playlist page
* 🌙 Dark / Light mode
* ✨ Modern interactive UI
* 📱 Responsive design
* 🔎 Search mood
* 🎨 Aesthetic music app interface

---

# 🖼 Preview

## Home Page

![Home Screenshot](public/screenshots/home_1.png)

---

## Mood Recommendation

![Mood Screenshot](public/screenshots/mood.png)

---

## Favorites Page

![Favorites Screenshot](public/screenshots/favorites.png)

---

# 🛠 Tech Stack

* Laravel 13
* PHP 8.3
* Spotify Web API
* MySQL
* Blade Template
* Vanilla JavaScript
* CSS3

---

# ⚙ Installation

Clone repository:

```bash
git clone https://github.com/USERNAME/Moodify-Laravel.git
```

Masuk ke folder project:

```bash
cd Moodify-Laravel
```

Install dependency:

```bash
composer install
```

Copy environment:

```bash
cp .env.example .env
```

Generate app key:

```bash
php artisan key:generate
```

Setup database di `.env`

```env
DB_DATABASE=moodify
DB_USERNAME=root
DB_PASSWORD=
```

Run migration:

```bash
php artisan migrate
```

Run server:

```bash
php artisan serve
```

---

# 🎧 Spotify API Setup

Tambahkan Spotify credentials ke `.env`

```env
SPOTIFY_CLIENT_ID=your_client_id
SPOTIFY_CLIENT_SECRET=your_client_secret
SPOTIFY_REDIRECT_URI=http://127.0.0.1:8000/auth/spotify/callback
```

---

# 📂 Project Structure

```bash
app/
resources/
routes/
public/
database/
```

---

# 🚀 Future Improvements

* AI mood detection
* Playlist generation
* Music player integration
* Admin CRUD panel
* Advanced recommendation system

---

# 👨‍💻 Developer

Made with music and sleep deprivation by Yoga Pratama.

---

# 📜 License

This project is for educational and portfolio purposes.

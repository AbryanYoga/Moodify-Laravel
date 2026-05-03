<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mood;

class MoodSeeder extends Seeder
{
    public function run(): void
    {
        Mood::create(['nama' => 'Senang']);
        Mood::create(['nama' => 'Sedih']);
        Mood::create(['nama' => 'Santai']);
        Mood::create(['nama' => 'Fokus']);
        Mood::create(['nama' => 'Galau']);
        Mood::create(['nama' => 'Semangat']);
    }
}
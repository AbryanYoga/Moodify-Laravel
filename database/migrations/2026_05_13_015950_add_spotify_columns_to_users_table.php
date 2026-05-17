<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('spotify_id')->nullable();

            $table->text('spotify_token')->nullable();

            $table->text('avatar')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'spotify_id',
                'spotify_token',
                'avatar'
            ]);

        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Halaman "link in bio" ala Taplink: /l/{slug} berisi logo, judul, dan daftar tombol link. */
    public function up(): void
    {
        Schema::create('shortlinks', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('handle')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('logo')->nullable();
            $table->string('background')->nullable();
            $table->string('button_color', 9)->nullable();
            // [{icon, title, subtitle, url}], icon = class Flaticon UIcons
            $table->json('links')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shortlinks');
    }
};

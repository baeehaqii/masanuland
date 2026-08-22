<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ponytail: single-row table, edited through one Filament page.
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name')->default('Masanuland');
            $table->string('logo')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->json('hero_badges')->nullable();
            $table->string('hero_note')->nullable();
            $table->text('tagline')->nullable();
            $table->text('about_text')->nullable();
            $table->json('about_points')->nullable();
            $table->string('about_video')->nullable();
            $table->json('stats')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('whatsapp_text')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->json('socials')->nullable();
            $table->text('map_embed')->nullable();
            $table->string('brochure_url')->nullable();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('tagline')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('price_from')->nullable();
            $table->unsignedBigInteger('price_before')->nullable();
            $table->string('price_note')->nullable();
            $table->json('badges')->nullable();
            $table->json('distances')->nullable();
            $table->json('features')->nullable();
            $table->json('gallery')->nullable();
            $table->string('card_image')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('site_plan_image')->nullable();
            $table->text('description')->nullable();
            $table->text('map_embed')->nullable();
            $table->string('map_url')->nullable();
            $table->string('brochure_url')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('house_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('price_label')->nullable();
            $table->json('specs')->nullable();
            $table->string('brochure_url')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('location')->nullable();
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->string('video_url')->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('house_types');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('settings');
    }
};

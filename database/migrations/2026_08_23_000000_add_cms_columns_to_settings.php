<?php

use App\Support\DefaultContent;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('favicon')->nullable()->after('logo');
            $table->string('logo_footer')->nullable()->after('logo');
            $table->json('menu_header')->nullable();
            $table->json('menu_footer')->nullable();
            $table->json('buttons')->nullable();
            $table->json('theme')->nullable();
            $table->json('seo')->nullable();
            $table->json('og')->nullable();
            $table->json('page_home')->nullable();
            $table->json('page_about')->nullable();
            $table->json('page_testimonials')->nullable();
        });

        // Ship the panel with the copy that used to be hard-coded in the React pages.
        DB::table('settings')->update(array_map(
            fn ($value) => json_encode($value),
            DefaultContent::all(),
        ));

        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('session_id', 64)->index();
            $table->string('platform', 16)->nullable();
            $table->timestamp('created_at')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'favicon', 'logo_footer', 'menu_header', 'menu_footer', 'buttons',
                'theme', 'seo', 'og', 'page_home', 'page_about', 'page_testimonials',
            ]);
        });
    }
};

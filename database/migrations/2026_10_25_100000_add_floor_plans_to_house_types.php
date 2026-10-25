<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Denah tambahan per tipe: [{image, label}], mis. "Denah Lantai 1". */
    public function up(): void
    {
        Schema::table('house_types', function (Blueprint $table) {
            $table->json('floor_plans')->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('house_types', function (Blueprint $table) {
            $table->dropColumn('floor_plans');
        });
    }
};

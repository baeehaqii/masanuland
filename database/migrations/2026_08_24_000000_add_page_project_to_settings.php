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
            // Judul section & label tombol di halaman detail perumahan.
            $table->json('page_project')->nullable()->after('page_about');
        });

        DB::table('settings')->update([
            'page_project' => json_encode(DefaultContent::all()['page_project']),
        ]);
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('page_project');
        });
    }
};

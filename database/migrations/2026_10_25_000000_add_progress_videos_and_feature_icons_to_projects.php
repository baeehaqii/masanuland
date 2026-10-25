<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fasilitas dulunya daftar teks (["One Gate System", ...]); sekarang tiap
     * fasilitas bisa punya ikon SVG, jadi bentuknya [{label, icon}]. Plus kolom
     * video progres pembangunan (YouTube Shorts) per bulan.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('progress_videos')->nullable()->after('update_video');
        });

        $this->mapFeatures(fn ($feature) => is_string($feature) ? ['label' => $feature, 'icon' => null] : $feature);
    }

    public function down(): void
    {
        $this->mapFeatures(fn ($feature) => is_array($feature) ? $feature['label'] : $feature);

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('progress_videos');
        });
    }

    private function mapFeatures(Closure $map): void
    {
        foreach (DB::table('projects')->whereNotNull('features')->select('id', 'features')->get() as $project) {
            DB::table('projects')->where('id', $project->id)->update([
                'features' => json_encode(array_map($map, json_decode($project->features, true) ?? [])),
            ]);
        }
    }
};

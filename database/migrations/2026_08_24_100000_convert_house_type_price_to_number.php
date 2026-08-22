<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Harga tipe rumah dulunya teks bebas ("Rp 450.000.000 ,-"), jadi angkanya
     * tidak bisa diformat maupun diurutkan — dan mudah masuk mentah seperti
     * "166000000". Sekarang angka; format rupiahnya dirakit saat ditampilkan.
     */
    public function up(): void
    {
        Schema::table('house_types', function (Blueprint $table) {
            $table->unsignedBigInteger('price')->nullable()->after('image');
        });

        foreach (DB::table('house_types')->select('id', 'price_label')->get() as $type) {
            $digits = preg_replace('/\D/', '', (string) $type->price_label);

            DB::table('house_types')->where('id', $type->id)
                ->update(['price' => $digits === '' ? null : (int) $digits]);
        }

        Schema::table('house_types', function (Blueprint $table) {
            $table->dropColumn('price_label');
        });
    }

    public function down(): void
    {
        Schema::table('house_types', function (Blueprint $table) {
            $table->string('price_label')->nullable()->after('image');
        });

        foreach (DB::table('house_types')->select('id', 'price')->get() as $type) {
            DB::table('house_types')->where('id', $type->id)->update([
                'price_label' => $type->price ? 'Rp '.number_format((int) $type->price, 0, ',', '.').' ,-' : null,
            ]);
        }

        Schema::table('house_types', function (Blueprint $table) {
            $table->dropColumn('price');
        });
    }
};

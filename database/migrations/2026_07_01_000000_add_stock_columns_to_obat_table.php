<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('obat', function (Blueprint $table) {
            if (!Schema::hasColumn('obat', 'nama_obat')) {
                $table->string('nama_obat')->nullable();
            }
            if (!Schema::hasColumn('obat', 'kemasan')) {
                $table->string('kemasan')->nullable();
            }
            if (!Schema::hasColumn('obat', 'harga')) {
                $table->integer('harga')->default(0);
            }
            if (!Schema::hasColumn('obat', 'stok')) {
                $table->integer('stok')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obat', function (Blueprint $table) {
            if (Schema::hasColumn('obat', 'stok')) {
                $table->dropColumn('stok');
            }
        });
    }
};

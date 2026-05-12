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
        Schema::table('buku_wisuda', function (Blueprint $table) {
            $table->string('tahun_akademik')->nullable();
            $table->string('nomor_sk')->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->string('nama_rektor')->nullable();
            $table->string('nip_rektor')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku_wisuda', function (Blueprint $table) {
            $table->dropColumn([
                'tahun_akademik',
                'nomor_sk',
                'tanggal_sk',
                'nama_rektor',
                'nip_rektor',
            ]);
        });
    }
};

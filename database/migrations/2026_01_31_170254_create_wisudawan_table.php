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
        Schema::create('wisudawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_buku')->constrained('buku_wisuda');
            $table->string('nama');
            $table->string('nim');
            $table->string('nomor');
            $table->string('ttl');
            $table->string('jenis_kelamin');
            $table->string('prodi');
            $table->string('fakultas');
            $table->double('ipk');
            $table->string('ka_yudisium');
            $table->text('judul_thesis');
            $table->string('foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wisudawan');
    }
};

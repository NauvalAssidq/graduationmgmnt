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
            $table->integerIncrements('wisudawan_id');
            $table->unsignedInteger('buku_wisuda_id');
            $table->string('nama');
            $table->char('nim', 20);
            $table->string('nomor');
            $table->string('ttl');
            $table->char('jenis_kelamin', 1);
            $table->string('prodi');
            $table->string('fakultas');
            $table->double('ipk');
            $table->string('ka_yudisium');
            $table->text('judul_thesis');
            $table->string('foto')->nullable();
            $table->timestamps();

            $table->foreign('buku_wisuda_id')->references('buku_wisuda_id')->on('buku_wisuda')->onDelete('cascade');
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

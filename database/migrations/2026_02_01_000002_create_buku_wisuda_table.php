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
        Schema::create('buku_wisuda', function (Blueprint $table) {
            $table->id();
            $table->string('nama_buku');
            $table->string('template_id')->nullable();
            $table->string('slug')->nullable();
            $table->date('tanggal_terbit');
            $table->string('gelombang');
            $table->string('status');
            $table->string('tahun');
            $table->string('file_pdf')->nullable();
            $table->timestamps();

            $table->foreign('template_id')->references('nama')->on('template_buku_wisuda')->nullOnDelete();
        });
    }

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buku_wisuda');
    }
};

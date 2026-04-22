<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_sources', function (Blueprint $table) {
            $table->integerIncrements('api_source_id');
            $table->string('nama_buku');
            $table->char('tahun', 4);
            $table->string('api_url');
            $table->unsignedInteger('buku_wisuda_id')->nullable();
            $table->timestamps();

            $table->foreign('buku_wisuda_id')
                  ->references('buku_wisuda_id')
                  ->on('buku_wisuda')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_sources');
    }
};

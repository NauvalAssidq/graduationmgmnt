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
        Schema::create('template_buku_wisuda', function (Blueprint $table) {
            $table->string('nama')->primary();
            $table->string('layout');
            $table->string('style');
            $table->longText('cover_html')->nullable();
            $table->longText('custom_css')->nullable();
            $table->timestamps();
        });
    }

    // Membuat tabel template_buku_wisuda dengan kolom berupa nama, layout, style, cover_html, custom_css, dan timestamps
    // nama adalah primary key

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_buku_wisuda');
    }
};

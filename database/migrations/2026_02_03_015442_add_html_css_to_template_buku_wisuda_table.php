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
        Schema::table('template_buku_wisuda', function (Blueprint $table) {
            $table->longText('cover_html')->nullable();
            $table->longText('custom_css')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('template_buku_wisuda', function (Blueprint $table) {
            $table->dropColumn(['cover_html', 'custom_css']);
        });
    }
};

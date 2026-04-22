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
        Schema::create('settings', function (Blueprint $table) {
            $table->integerIncrements('setting_id');
            $table->unsignedInteger('admin_id');
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
            $table->foreign('admin_id')->references('admin_id')->on('admin')->onDelete('cascade');
            $table->unique(['admin_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropUnique('settings_admin_id_key_unique');
            $table->dropColumn('admin_id');
            $table->unique('key');
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropUnique(['key']);
            $table->unsignedInteger('admin_id')->after('setting_id');
            $table->foreign('admin_id')->references('admin_id')->on('admin')->onDelete('cascade');
            $table->unique(['admin_id', 'key']);
        });
    }
};

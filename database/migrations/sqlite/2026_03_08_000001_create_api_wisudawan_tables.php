<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'sqlite_api';

    public function up(): void
    {
        Schema::connection('sqlite_api')->create('wisudawan', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('nama');
            $table->string('nim', 20);
            $table->string('nomor')->nullable();
            $table->string('ttl')->nullable();
            $table->string('jenis_kelamin', 1)->nullable();
            $table->string('prodi');
            $table->string('fakultas');
            $table->double('ipk')->nullable();
            $table->string('ka_yudisium')->nullable();
            $table->text('judul_thesis')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('sqlite_api')->dropIfExists('wisudawan');
    }
};

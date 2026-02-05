<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Rename existing table
        Schema::rename('wisudawan', 'wisudawan_old');

        // 2. Create new table with correct FK
        Schema::create('wisudawan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_buku');
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

            // Correct Foreign Key
            $table->foreign('id_buku')->references('id')->on('buku_wisuda')->onDelete('cascade');
        });

        // 3. Copy data
        DB::statement('INSERT INTO wisudawan (id, id_buku, nama, nim, nomor, ttl, jenis_kelamin, prodi, fakultas, ipk, ka_yudisium, judul_thesis, foto, created_at, updated_at) 
                       SELECT id, id_buku, nama, nim, nomor, ttl, jenis_kelamin, prodi, fakultas, ipk, ka_yudisium, judul_thesis, foto, created_at, updated_at FROM wisudawan_old');

        // 4. Drop old table
        Schema::dropIfExists('wisudawan_old');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Standard rollback not easily possible as we are fixing a broken state. 
        // We will just drop the table.
        Schema::dropIfExists('wisudawan');
    }
};

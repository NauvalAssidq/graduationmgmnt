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
        // 1. Rename the existing table
        Schema::rename('buku_wisuda', 'buku_wisuda_old');

        // 2. Create the new table with nullable 'file_pdf'
        Schema::create('buku_wisuda', function (Blueprint $table) {
            $table->id();
            $table->string('nama_buku');
            $table->date('tanggal_terbit');
            $table->string('gelombang');
            $table->string('status');
            $table->string('tahun');
            $table->string('file_pdf')->nullable(); // Changed to nullable
            $table->timestamps();
        });

        // 3. Copy data from old table to new table
        // We use raw insert select. Note: timestamps are included in *
        DB::statement('INSERT INTO buku_wisuda (id, nama_buku, tanggal_terbit, gelombang, status, tahun, file_pdf, created_at, updated_at) 
                       SELECT id, nama_buku, tanggal_terbit, gelombang, status, tahun, file_pdf, created_at, updated_at FROM buku_wisuda_old');

        // 4. Drop the old table
        Schema::dropIfExists('buku_wisuda_old');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert by making it not null again (if needed) works similarly
        Schema::rename('buku_wisuda', 'buku_wisuda_new');

        Schema::create('buku_wisuda', function (Blueprint $table) {
            $table->id();
            $table->string('nama_buku');
            $table->date('tanggal_terbit');
            $table->string('gelombang');
            $table->string('status');
            $table->string('tahun');
            $table->string('file_pdf'); // Reverted to not null
            $table->timestamps();
        });

        // Copy back. CAUTION: If there are NULLs now, this will fail in strict mode. 
        // But for down(), we assume we want to restore previous state.
        // We might validly crash here if data doesn't fit schema, which is expected.
        DB::statement('INSERT INTO buku_wisuda SELECT * FROM buku_wisuda_new');

        Schema::dropIfExists('buku_wisuda_new');
    }
};

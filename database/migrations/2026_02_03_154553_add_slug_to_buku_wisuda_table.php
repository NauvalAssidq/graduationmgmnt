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
        Schema::table('buku_wisuda', function (Blueprint $table) {
            $table->string('slug')->after('nama_buku')->nullable();
        });

        // Fill existing slugs
        $books = \Illuminate\Support\Facades\DB::table('buku_wisuda')->get();
        foreach($books as $book){ 
             \Illuminate\Support\Facades\DB::table('buku_wisuda')
                ->where('id', $book->id)
                ->update(['slug' => \Illuminate\Support\Str::slug($book->nama_buku) . '-' . $book->id]); 
             // appended ID ensures uniqueness for existing duplicate names if any
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buku_wisuda', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};

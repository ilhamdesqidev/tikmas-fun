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
        // Ubah enum status untuk menambahkan 'habis'
        DB::statement("ALTER TABLE vouchers MODIFY COLUMN status ENUM('aktif', 'tidak_aktif', 'kadaluarsa', 'habis') DEFAULT 'aktif'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum status sebelumnya
        // Update dulu semua status 'habis' ke 'kadaluarsa'
        DB::table('vouchers')->where('status', 'habis')->update(['status' => 'kadaluarsa']);
        
        DB::statement("ALTER TABLE vouchers MODIFY COLUMN status ENUM('aktif', 'tidak_aktif', 'kadaluarsa') DEFAULT 'aktif'");
    }
};
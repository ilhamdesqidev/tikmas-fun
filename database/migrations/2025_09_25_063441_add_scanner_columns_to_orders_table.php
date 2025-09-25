<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Cek tipe kolom status saat ini
        $columns = DB::select("SHOW COLUMNS FROM orders WHERE Field = 'status'");
        
        if (!empty($columns)) {
            $currentType = $columns[0]->Type;
            
            // Jika kolom status adalah ENUM, kita perlu menambahkan 'used'
            if (strpos($currentType, 'enum') !== false) {
                // Tambahkan 'used' ke ENUM yang sudah ada
                DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'success', 'used', 'expired', 'canceled', 'denied', 'challenge') DEFAULT 'pending'");
            } else {
                // Jika VARCHAR, pastikan panjangnya cukup
                Schema::table('orders', function (Blueprint $table) {
                    $table->string('status', 20)->default('pending')->change();
                });
            }
        }

        // Tambahkan kolom used_at dan used_by_staff jika belum ada
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'used_at')) {
                $table->timestamp('used_at')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('orders', 'used_by_staff')) {
                $table->string('used_by_staff', 100)->nullable()->after('used_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Kembalikan ENUM ke nilai asli (tanpa 'used')
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'success', 'expired', 'canceled', 'denied', 'challenge') DEFAULT 'pending'");
        
        // Drop kolom tambahan
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'used_at')) {
                $table->dropColumn('used_at');
            }
            
            if (Schema::hasColumn('orders', 'used_by_staff')) {
                $table->dropColumn('used_by_staff');
            }
        });
    }
};
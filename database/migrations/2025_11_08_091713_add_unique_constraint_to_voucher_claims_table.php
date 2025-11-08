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
        Schema::table('voucher_claims', function (Blueprint $table) {
            // Tambahkan unique constraint untuk kombinasi voucher_id dan user_phone
            $table->unique(['voucher_id', 'user_phone'], 'unique_phone_per_voucher');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voucher_claims', function (Blueprint $table) {
            $table->dropUnique('unique_phone_per_voucher');
        });
    }
};
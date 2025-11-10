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
            $table->string('user_domisili')->nullable()->after('user_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voucher_claims', function (Blueprint $table) {
            $table->dropColumn('user_domisili');
        });
    }
};
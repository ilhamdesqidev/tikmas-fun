<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('voucher_claims', function (Blueprint $table) {
            $table->timestamp('scanned_at')->nullable()->after('created_at');
            $table->unsignedBigInteger('scanned_by')->nullable()->after('scanned_at');
            
            $table->foreign('scanned_by')->references('id')->on('staff_codes')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('voucher_claims', function (Blueprint $table) {
            $table->dropForeign(['scanned_by']);
            $table->dropColumn(['scanned_at', 'scanned_by']);
        });
    }
};
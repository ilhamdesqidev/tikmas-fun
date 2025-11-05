<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuotaToVouchersTable extends Migration
{
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->integer('quota')->nullable()->after('expiry_date')->comment('Kuota voucher, null berarti unlimited');
            $table->boolean('is_unlimited')->default(true)->after('quota');
        });
    }

    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropColumn(['quota', 'is_unlimited']);
        });
    }
}
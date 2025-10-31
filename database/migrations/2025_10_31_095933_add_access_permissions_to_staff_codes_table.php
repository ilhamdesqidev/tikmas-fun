<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('staff_codes', function (Blueprint $table) {
            $table->json('access_permissions')->nullable()->after('role');
            // access_permissions will store: {"tickets": true, "vouchers": true}
        });
    }

    public function down()
    {
        Schema::table('staff_codes', function (Blueprint $table) {
            $table->dropColumn('access_permissions');
        });
    }
};
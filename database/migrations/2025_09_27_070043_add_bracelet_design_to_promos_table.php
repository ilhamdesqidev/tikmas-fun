<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('promos', function (Blueprint $table) {
        $table->string('bracelet_design')->nullable()->after('image');
    });
}

public function down()
{
    Schema::table('promos', function (Blueprint $table) {
        $table->dropColumn('bracelet_design');
    });
}
};

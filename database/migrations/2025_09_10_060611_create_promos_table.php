<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->text('description');
            $table->text('terms_conditions')->nullable();
            $table->decimal('original_price', 12, 2);
            $table->decimal('promo_price', 12, 2);
            $table->integer('discount_percent')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['draft', 'coming_soon', 'active', 'inactive', 'expired'])->default('draft');
            $table->integer('quota')->nullable();
            $table->integer('sold_count')->default(0);
            $table->string('category')->default('general');
            $table->boolean('featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('promos');
    }
};
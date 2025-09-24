<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('promo_id')->constrained()->onDelete('cascade');
            $table->string('invoice_number')->nullable()->unique();
            $table->string('customer_name');
            $table->string('whatsapp_number');
            $table->string('branch');
            $table->date('visit_date');
            $table->integer('ticket_quantity');
            $table->decimal('total_price', 15, 2);
            $table->enum('status', ['pending', 'success', 'challenge', 'denied', 'expired', 'canceled'])->default('pending');
            $table->string('snap_token')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
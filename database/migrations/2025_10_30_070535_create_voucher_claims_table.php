<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained()->onDelete('cascade');
            $table->string('user_name');
            $table->string('user_phone');
            $table->string('unique_code')->unique();
            $table->boolean('is_used')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_claims');
    }
};

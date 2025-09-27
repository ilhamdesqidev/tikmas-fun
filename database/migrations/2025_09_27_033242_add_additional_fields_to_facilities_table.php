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
        Schema::table('facilities', function (Blueprint $table) {
            $table->string('duration')->nullable()->after('description');
            $table->string('age_range')->nullable()->after('duration');
            $table->json('gallery_images')->nullable()->after('age_range');
            $table->string('category')->default('wahana')->after('gallery_images');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn(['duration', 'age_range', 'gallery_images', 'category']);
        });
    }
};
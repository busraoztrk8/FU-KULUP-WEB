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
        // 1. Add fields to clubs
        Schema::table('clubs', function (Blueprint $table) {
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();
            $table->string('founder_name')->nullable();
            $table->string('established_year')->nullable();
        });

        // 2. Add title to club_members
        Schema::table('club_members', function (Blueprint $table) {
            $table->string('title')->nullable();
        });

        // 3. Create club_images table for the gallery
        Schema::create('club_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('club_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('club_images');

        Schema::table('club_members', function (Blueprint $table) {
            $table->dropColumn('title');
        });

        Schema::table('clubs', function (Blueprint $table) {
            $table->dropColumn(['mission', 'vision', 'founder_name', 'established_year']);
        });
    }
};

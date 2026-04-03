<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->string('image')->nullable();

            // Timing
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();

            // Location
            $table->string('location')->nullable();
            $table->string('location_url')->nullable();

            // Organization (Handled in app)
            $table->unsignedBigInteger('club_id');
            $table->unsignedBigInteger('category_id')->nullable();

            // Stats & Control
            $table->integer('max_participants')->nullable();
            $table->integer('current_participants')->default(0);
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('published');
            $table->boolean('is_featured')->default(false);

            $table->softDeletes();
            $table->timestamps();

        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};

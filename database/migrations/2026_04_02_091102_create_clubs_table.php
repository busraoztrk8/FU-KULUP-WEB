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
        Schema::create('clubs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();

            // Foreign keys (Handled in app)
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('president_id')->nullable();

            // Stats (can be dynamic, but cached here)
            $table->integer('member_count')->default(0);
            $table->integer('event_count')->default(0);

            $table->boolean('is_active')->default(true);
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
        Schema::dropIfExists('clubs');
    }
};

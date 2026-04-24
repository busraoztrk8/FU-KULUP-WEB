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
        // 1. Sliders Table
        Schema::table('sliders', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->string('subtitle_en')->nullable()->after('subtitle');
            $table->string('button_text_en')->nullable()->after('button_text');
        });

        // 2. Events Table
        Schema::table('events', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('description_en')->nullable()->after('description');
            $table->string('short_description_en')->nullable()->after('short_description');
            $table->string('location_en')->nullable()->after('location');
        });

        // 3. Clubs Table
        Schema::table('clubs', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->text('description_en')->nullable()->after('description');
            $table->string('short_description_en')->nullable()->after('short_description');
        });

        // 4. News Table
        Schema::table('news', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('content_en')->nullable()->after('content');
        });

        // 5. Announcements Table
        Schema::table('announcements', function (Blueprint $table) {
            $table->string('title_en')->nullable()->after('title');
            $table->text('content_en')->nullable()->after('content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'subtitle_en', 'button_text_en']);
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'description_en', 'short_description_en', 'location_en']);
        });

        Schema::table('clubs', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'description_en', 'short_description_en']);
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'content_en']);
        });

        Schema::table('announcements', function (Blueprint $table) {
            $table->dropColumn(['title_en', 'content_en']);
        });
    }
};

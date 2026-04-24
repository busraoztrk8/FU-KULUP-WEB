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
        Schema::table('clubs', function (Blueprint $table) {
            $table->text('mission_en')->nullable()->after('mission');
            $table->text('vision_en')->nullable()->after('vision');
            $table->text('activities_en')->nullable()->after('activities');
            $table->string('founder_name_en')->nullable()->after('founder_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->dropColumn(['mission_en', 'vision_en', 'activities_en', 'founder_name_en']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->string('whatsapp_url', 500)->nullable()->after('facebook_url');
            $table->string('channel_url', 500)->nullable()->after('whatsapp_url');
        });
    }

    public function down(): void
    {
        Schema::table('clubs', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_url', 'channel_url']);
        });
    }
};

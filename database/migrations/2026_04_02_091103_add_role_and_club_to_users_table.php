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
        Schema::table('users', function (Blueprint $table) {
            // Role and Club assignment (Handled in app)
            $table->unsignedBigInteger('role_id')->nullable()->default(3)->after('email');
            $table->unsignedBigInteger('club_id')->nullable()->after('role_id');

            // Profile fields
            $table->string('profile_photo')->nullable()->after('password');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
            $table->dropForeign(['club_id']);
            $table->dropColumn('club_id');
            $table->dropColumn('profile_photo');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // users → roles
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('role_id')
                ->references('id')->on('roles')
                ->nullOnDelete();

            $table->foreign('club_id')
                ->references('id')->on('clubs')
                ->nullOnDelete();
        });

        // clubs → categories, users (president)
        Schema::table('clubs', function (Blueprint $table) {
            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->nullOnDelete();

            $table->foreign('president_id')
                ->references('id')->on('users')
                ->nullOnDelete();
        });

        // events → clubs, categories
        Schema::table('events', function (Blueprint $table) {
            $table->foreign('club_id')
                ->references('id')->on('clubs')
                ->cascadeOnDelete();

            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->nullOnDelete();
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['club_id']);
            $table->dropForeign(['category_id']);
        });

        Schema::table('clubs', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['president_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropForeign(['club_id']);
        });

        Schema::enableForeignKeyConstraints();
    }
};

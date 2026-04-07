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
        Schema::create('roles', function (Blueprint $label) {
            $label->id();
            $label->string('name')->unique(); // admin, editor, student
            $label->string('label')->nullable(); // Admin, Kulüp Başkanı, Öğrenci
            $label->softDeletes();
            $label->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('club_registration_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('club_member_id');
            $table->unsignedBigInteger('club_form_field_id');
            $table->text('value')->nullable(); // Form alanının cevabı
            $table->timestamps();

            $table->foreign('club_member_id')->references('id')->on('club_members')->onDelete('cascade');
            $table->foreign('club_form_field_id')->references('id')->on('club_form_fields')->onDelete('cascade');
            $table->index('club_member_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('club_registration_data');
    }
};

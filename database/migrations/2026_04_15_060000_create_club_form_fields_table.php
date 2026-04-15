<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('club_form_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('club_id');
            $table->string('label');          // Alan adı (örn: "Adınız - Soyadınız")
            $table->string('type')->default('text'); // text, email, tel, textarea, checkbox, select
            $table->string('placeholder')->nullable(); // Placeholder text
            $table->json('options')->nullable(); // Select tipi için seçenekler
            $table->boolean('is_required')->default(true);
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('club_id')->references('id')->on('clubs')->onDelete('cascade');
            $table->index(['club_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('club_form_fields');
    }
};

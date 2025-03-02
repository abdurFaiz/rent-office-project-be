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
        Schema::create('office_spaces', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->text('about');
            $table->string('thumbnail');
            $table->string('slug')->unique();
            $table->boolean('is_open');
            $table->boolean('is_full_booked');
            $table->foreignId('city_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('price');
            $table->unsignedInteger('duration');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('office_spaces');
    }
};

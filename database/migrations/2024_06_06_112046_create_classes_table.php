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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // default value is (<MENTOR> and <STUDENT> Class)
            $table->foreignId('appointment_id')->constrained();
            $table->string('class_id')->nullable();; // from api
            $table->string('start_time')->nullable();; // from api
            $table->string('end_time')->nullable();; // from api
            $table->string('duration')->nullable();; // from api
            $table->string('status')->nullable();; // from api
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};

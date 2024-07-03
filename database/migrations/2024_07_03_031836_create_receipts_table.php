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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('sender_name');
            $table->foreignId('receiver_id');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('receiver_name');
            $table->decimal('amount', 8, 2)->default(0);
            $table->decimal('service_charge', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};

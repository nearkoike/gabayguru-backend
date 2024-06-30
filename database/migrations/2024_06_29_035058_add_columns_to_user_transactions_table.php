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
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->string('reference_number')->nullable();
            $table->string('screenshot')->nullable();
            $table->string('sender_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->boolean('status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_transactions', function (Blueprint $table) {
            $table->dropColumn('reference_number');
            $table->dropColumn('screenshot');
            $table->dropColumn('sender_name');
            $table->dropColumn('account_name');
            $table->dropColumn('account_number');
            $table->dropColumn('status');
        });
    }
};

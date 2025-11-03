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
        Schema::create('challenge_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_id')->constrained('challenges')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->smallInteger('progress_percent')->default(0);
            $table->integer('streak')->default(0);
            $table->enum('role', ['creator', 'member'])->default('member');
            $table->dateTime('personal_start_date')->nullable();
            $table->dateTime('personal_end_date')->nullable();
            $table->enum('status', ['active', 'kicked','left'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_participants');
    }
};

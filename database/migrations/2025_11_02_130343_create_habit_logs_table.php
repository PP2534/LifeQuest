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
        Schema::create('habit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('habit_participant_id')->constrained('habit_participants')->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['done', 'missed'])->default('missed');
            $table->string('proof_image')->nullable();
            $table->timestamps();
            $table->unique(['date','habit_participant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habit_logs');
    }
};

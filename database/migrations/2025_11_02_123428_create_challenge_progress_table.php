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
        Schema::create('challenge_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('challenge_participant_id')->constrained('challenge_participants')->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['done', 'missed'])->default('missed');
            $table->string('proof_image')->nullable();
            $table->timestamps();
            $table->unique(['date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenge_progress');
    }
};

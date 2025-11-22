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
        Schema::table('user_xp_logs', function (Blueprint $table) {
            $table->integer('related_id')->nullable();
            $table->string('related_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_xp_logs', function (Blueprint $table) {
            $table->dropColumn('related_id');
            $table->dropColumn('related_type');
        });
    }
};

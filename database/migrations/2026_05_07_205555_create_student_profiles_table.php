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
        Schema::create('student_profiles', function (Blueprint $table) {
        $table->id();

        $table->foreignId('student_id')
            ->unique()
            ->constrained()
            ->cascadeOnDelete();

        $table->foreignId('current_level_id')
            ->nullable()
            ->constrained('levels')
            ->nullOnDelete();

        $table->integer('current_points')->default(0);

        $table->integer('current_streak')->default(0);

        $table->integer('longest_streak')->default(0);

        $table->integer("total_games_played")->default(0);

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};

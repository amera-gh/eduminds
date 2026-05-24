<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_learning_topics', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('learning_topic_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('priority')->default(0);

            $table->timestamps();

            // A student can only have each topic once
            $table->unique(['student_id', 'learning_topic_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_learning_topics');
    }
};
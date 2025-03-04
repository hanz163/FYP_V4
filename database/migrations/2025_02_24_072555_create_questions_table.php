<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('questions', function (Blueprint $table) {
            $table->string('QuestionID', 10)->primary(); // Custom ID
            $table->string('DifficultyID'); // Foreign key to difficulty
            $table->string('partID'); // Foreign key to part
            $table->text('question_text'); // Question text
            $table->timestamps();

            $table->foreign('DifficultyID')->references('DifficultyID')->on('difficulties')->onDelete('cascade');
            $table->foreign('partID')->references('partID')->on('parts')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('questions');
    }
};

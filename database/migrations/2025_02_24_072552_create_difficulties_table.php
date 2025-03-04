<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('difficulties', function (Blueprint $table) {
            $table->string('DifficultyID', 10)->primary(); // Custom ID
            $table->string('partID'); // Foreign key from parts table
            $table->enum('level', ['easy', 'normal', 'hard']); // Difficulty levels
            $table->timestamps();

            $table->foreign('partID')->references('partID')->on('parts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('difficulties');
    }
};

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
        Schema::create('chapters', function (Blueprint $table) {
            $table->string('chapterID')->primary();
            $table->string('chapterName', 30);
            $table->text('description');
            $table->string('courseID');
            $table->integer('position')->default(0);
            $table->string('image')->nullable(); 
            $table->timestamps();

            // Foreign key reference to courses table
            $table->foreign('courseID')->references('courseID')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};

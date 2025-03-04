<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('courses', function (Blueprint $table) {
            $table->string('courseID')->primary();
            $table->string('courseName');
            $table->text('description');
            $table->string('category');
            $table->integer('studentCount')->default(0);
            $table->integer('capacityOffered')->default(0);
            $table->string('teacherID', 6);
            $table->string('image')->nullable();
            $table->integer('display_order')->nullable();
            $table->foreign('teacherID')->references('teacherID')->on('teachers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('courses');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('student_course', function (Blueprint $table) {
            $table->string('studentID', 6);
            $table->string('courseID');
            $table->integer('progress')->default(0);
            $table->boolean('is_completed')->default(false);
            $table->integer('order')->default(0);
            $table->foreign('studentID')->references('studentID')->on('students')->onDelete('cascade');
            $table->foreign('courseID')->references('courseID')->on('courses')->onDelete('cascade');

            // Composite primary key
            $table->primary(['studentID', 'courseID']);

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('student_course');
    }
};

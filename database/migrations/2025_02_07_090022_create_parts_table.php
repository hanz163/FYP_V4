<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void {
        Schema::create('parts', function (Blueprint $table) {
            $table->string('partID', 10)->primary(); // Custom partID (P00001)
            $table->string('chapterID'); // Foreign Key to chapters
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('order')->default(1);
            $table->timestamps();

            $table->foreign('chapterID')->references('chapterID')->on('chapters')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('parts');
    }
};

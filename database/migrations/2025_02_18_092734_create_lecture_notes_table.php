<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('lecture_notes', function (Blueprint $table) {
            $table->id();
            $table->string('partID');
            $table->string('title');
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('partID')->references('partID')->on('parts')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('lecture_notes');
    }
};

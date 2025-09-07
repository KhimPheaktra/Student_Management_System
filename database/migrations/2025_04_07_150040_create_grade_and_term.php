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
        Schema::create('terms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Term 1", "Term 2"
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->default('ACT'); 
            $table->timestamps();
        });

        Schema::create('subject_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('subject_id');
            $table->foreign('subject_id')->references('id')->on('subjects');
            $table->unsignedBigInteger('term_id');
            $table->foreign('term_id')->references('id')->on('terms');
            $table->unsignedBigInteger('gen_id');
            $table->foreign('gen_id')->references('id')->on('generations');
            $table->float('midterm');
            $table->float('final');
            $table->float('total');
            $table->string('grade'); 
            $table->string('status')->default('ACT');
            $table->timestamps();
        });

        // Term grades table - for term averages
        Schema::create('term_grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('students');
            $table->unsignedBigInteger('term_id');
            $table->foreign('term_id')->references('id')->on('terms');
            $table->unsignedBigInteger('gen_id')->nullable();
            $table->foreign('gen_id')->references('id')->on('generations');
            $table->float('average_score'); // Average of all subject scores for this term
            $table->string('grade'); // Overall grade for the term
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('term_grades');
        Schema::dropIfExists('subject_scores');
        Schema::dropIfExists('terms');
    }
};

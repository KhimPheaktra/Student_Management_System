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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_code')->unique();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender_id')->nullable();
            $table->foreign('gender_id')->references('id')->on('genders');
            $table->date('dob')->nullable();
            $table->unsignedBigInteger('pob')->nullable();
            $table->foreign('pob')->references('id')->on('provices');
            $table->string('phone')->nullable()->unique();
            $table->string('parent_phone')->nullable();
            $table->string('image')->nullable();
            $table->date('enroll_at')->nullable();
            $table->unsignedBigInteger('major_id');
            $table->foreign('major_id')->references('id')->on('majors')->onDelete('set null');
            $table->unsignedBigInteger('gen_id')->nullable();
            $table->foreign('gen_id')->references('id')->on('generations');
            $table->string('status')->default('ACT'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student');
    }
};

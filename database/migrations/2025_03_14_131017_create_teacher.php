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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->date('dob')->nullable();
            $table->unsignedBigInteger('pob')->nullable();
            $table->foreign('pob')->references('id')->on('provices');
            $table->unsignedBigInteger('gender_id');
            $table->foreign('gender_id')->references('id')->on('genders');
            $table->unsignedBigInteger('position_id')->nullable();
            $table->foreign('position_id')->references('id')->on('positions');
            $table->String('phone')->nullable()->unique();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('salary_id')->nullable();
            $table->foreign('salary_id') // And this
            ->references('id')
            ->on('teacher_salary')
            ->onDelete('set null');
            $table->string('status')->default('ACT'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};

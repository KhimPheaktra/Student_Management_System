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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->unsignedBigInteger('position_id')->nullable();
            $table->foreign('position_id')->references('id')->on('positions');
            $table->date('dob');
            $table->unsignedBigInteger('gender_id')->nullable();
            $table->foreign('gender_id')->references('id')->on('genders');
            $table->unsignedBigInteger('pob')->nullable();
            $table->foreign('pob')->references('id')->on('provices');
            $table->string('phone')->nullable()->unique();
            $table->unsignedBigInteger('salary_id')->nullable(); 
            $table->foreign('salary_id') // And this
            ->references('id')
            ->on('employee_salary')
            ->onDelete('set null'); 
            $table->string('image')->nullable();
            $table->string('status')->default('ACT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

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
        Schema::create('teacher_salary', function (Blueprint $table) {
            $table->id();
            $table->double('salary');
            $table->string('status')->default('ACT');
            $table->timestamps();
        });

        Schema::create('employee_salary', function (Blueprint $table) {
            $table->id();
            $table->double('salary');
            $table->string('status')->default('ACT');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_salary');
        Schema::dropIfExists('employee_salary');
    }
};

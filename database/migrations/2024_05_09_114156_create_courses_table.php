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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_category_id')->constrained()->onDelete('cascade');
            $table->string('course_name');
            $table->string('language');
            $table->string('course_details');
            $table->string('course_time_length');
            $table->string('price');
            $table->string('max_student_length')->nullable();
            $table->string('skill_Level');
            $table->string('address')->nullable();
            $table->string('thumbnail');
            $table->json('career_opportunities');
            $table->json('curriculum');
            $table->json('tools');
            $table->json('job_position');
            $table->boolean('popular_section');
            $table->string('status');
            $table->string('course_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

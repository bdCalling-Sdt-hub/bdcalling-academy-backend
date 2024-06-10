<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('add_students', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->nullable();
            $table->integer('batch_id')->nullable();
            $table->integer('user_id');
            $table->integer('course_id')->nullable();
            $table->string('status')->default('pending');
            $table->string('phone')->nullable();
            $table->string('gender')->nullable();
            $table->text('riligion')->nullable();
            $table->date('registration_date')->nullable();
            $table->date('dob')->nullable();
            $table->string('blood_group')->nullable();
            $table->text('address')->nullable();
            $table->string('add_by')->nullable();
            $table->string('student_type')->nullable();
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_students');
    }
};

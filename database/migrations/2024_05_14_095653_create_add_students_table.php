<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('add_students', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->nullable();
            $table->integer('batch_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('phone');
            $table->string('gender');
            $table->text('riligion');
            $table->date('registration_date')->nullable();
            $table->date('dob');
            $table->string('blood_group');
            $table->text('address');
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

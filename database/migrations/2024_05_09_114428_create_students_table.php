<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->string('phone_number')->nullable();
            $table->string('gender')->nullable();
            $table->text('religion')->nullable();
            $table->date('registration_date')->nullable();
            $table->date('dob')->nullable();
            $table->string('blood_group')->nullable();
            $table->text('address')->nullable();
            $table->string('add_by')->nullable();
            $table->string('student_type')->default('auth');
            $table->json('messages')->nullable();
            $table->string('event_name')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

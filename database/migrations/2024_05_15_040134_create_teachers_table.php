<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_category_id')->constrained()->onDelete('cascade');
            $table->string('phone_number');
            $table->string('designation');
            $table->string('expert');
            $table->text('image')->nullable();
            $table->string('created_by')->nullable();
            $table->string('status')->nullable();
            $table->string('payment_type');
            $table->string('payment_method');
            $table->string('payment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};

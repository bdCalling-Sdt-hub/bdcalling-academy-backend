<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id');
            $table->string('batch_id');
            $table->string('batch_name')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('seat_limit');
            $table->integer('seat_left');
            $table->string('image');
            $table->double('discount_price')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};

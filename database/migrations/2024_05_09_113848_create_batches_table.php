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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_id');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('batch_name');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->unsignedInteger('total_seat'); // Changed to integer
            $table->unsignedInteger('seat_left')->nullable(); // Changed to integer and nullable
            $table->float('discount')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};

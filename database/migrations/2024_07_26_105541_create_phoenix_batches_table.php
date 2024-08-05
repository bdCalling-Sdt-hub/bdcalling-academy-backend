<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('phoenix_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id');
            $table->string('batch_id');
            $table->string('batch_name')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('seat_limit')->nullable();
            $table->integer('seat_left')->nullable();
            $table->string('image');
            $table->string('cost_status')->nullable();
            $table->string('cost')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phoenix_batches');
    }
};

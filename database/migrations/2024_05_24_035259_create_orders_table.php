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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('add_student_id');
            $table->integer('batch_id');
            $table->integer('course_id');
            $table->string('course_fee');
            $table->string('discount_price')->nullable();
            $table->string('price');                        
            $table->string('amount');
            $table->string('due');
            $table->string('discount_referance')->nullable();
            $table->string('gateway_name');            
            $table->string('transaction_id');
            $table->string('currency');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

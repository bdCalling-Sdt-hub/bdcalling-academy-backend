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
//            $table->integer('user_id');
            $table->foreignId('student_id');
            $table->foreignId('batch_id');
//            $table->integer('course_id');
            $table->string('course_fee');
            $table->string('discount_price')->nullable();
            $table->string('price');
            $table->string('amount');
            $table->string('due');
            $table->string('discount_reference')->nullable();
            $table->string('gateway_name');
            $table->json('installment_date')->nullable();
            $table->string('payment_type');
            $table->string('transaction_id')->nullable();
            $table->string('currency')->default('BDT');
            $table->string('status')->nullable();
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

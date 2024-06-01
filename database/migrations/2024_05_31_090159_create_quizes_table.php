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
        Schema::create('quizes', function (Blueprint $table) {
            $table->id();
            $table->integer('course_module_id');            
            $table->string('questions');            
            $table->string('currect_ans');
            $table->string('opt_1');
            $table->string('opt_2');
            $table->string('opt_3');
            $table->string('opt_4');
            $table->integer('mark'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizes');
    }
};

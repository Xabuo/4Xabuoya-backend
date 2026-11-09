<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('ratings_and_reviews', function (Blueprint $table) {
    $table->id();

    $table->foreignId('product_id')
          ->constrained('product')
          ->cascadeOnDelete();

    $table->foreignId('customer_id')
          ->constrained('customer')
          ->cascadeOnDelete();

    $table->integer('score');
    $table->text('comment')->nullable();
    $table->date('date')->nullable();

    $table->timestamps();
});
    }


    public function down(): void
    {
        Schema::dropIfExists('ratings_and_reviews');
    }
};

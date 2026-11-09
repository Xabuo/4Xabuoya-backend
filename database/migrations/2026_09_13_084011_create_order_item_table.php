<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
       Schema::create('order_item', function (Blueprint $table) {
    $table->id();

    $table->foreignId('order_id')
          ->constrained('order')
          ->cascadeOnDelete();

    $table->foreignId('product_id')
          ->constrained('product')
          ->cascadeOnDelete();

    $table->integer('quantity');
    $table->decimal('unit_price', 10, 2);

    $table->timestamps();
});
    }

   
    public function down(): void
    {
        Schema::dropIfExists('order_item');
    }
};

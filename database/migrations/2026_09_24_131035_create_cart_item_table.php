<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_item', function (Blueprint $table) {
            $table->id();

            $table->foreignId('shopping_cart_id')
                  ->constrained('shopping_cart')
                  ->cascadeOnDelete();

            $table->foreignId('product_id')
                  ->constrained('product')
                  ->cascadeOnDelete();

            $table->integer('quantity')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_item');
    }
};
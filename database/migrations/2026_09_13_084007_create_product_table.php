<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
{
    Schema::create('product', function (Blueprint $table) {
        $table->id();

        $table->foreignId('category_id')
              ->constrained('category')
              ->cascadeOnDelete();

        $table->string('product_name');
        $table->text('product_description')->nullable();
        $table->decimal('price', 10, 2);
        $table->integer('stock_level')->default(0);
        $table->string('image_url')->nullable();
        $table->text('product_review')->nullable();

        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('product');
}
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('customer_notification', function (Blueprint $table) {
    $table->id();

    $table->foreignId('customer_id')
          ->constrained('customer')
          ->cascadeOnDelete();

    $table->foreignId('order_id')
          ->nullable()
          ->constrained('order')
          ->nullOnDelete();

    $table->string('order_update');
    $table->text('admin_message')->nullable();
    $table->dateTime('sent_date')->nullable();

    $table->timestamps();
});
    }

   
    public function down(): void
    {
        Schema::dropIfExists('customer_notification');
    }
};

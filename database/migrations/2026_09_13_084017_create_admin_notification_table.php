<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
    {
        Schema::create('admin_notification', function (Blueprint $table) {
    $table->id();

    $table->foreignId('admin_id')
          ->constrained('admin')
          ->cascadeOnDelete();

    $table->foreignId('order_id')
          ->nullable()
          ->constrained('order')
          ->nullOnDelete();

    $table->string('title');
    $table->text('body');
    $table->dateTime('sent_date')->nullable();

    $table->timestamps();
});
    }

   
    public function down(): void
    {
        Schema::dropIfExists('admin_notification');
    }
};

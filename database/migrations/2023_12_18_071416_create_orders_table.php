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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->enum('status', ['To Ship', 'To Receive', 'Completed'])->default('To Ship');
            $table->json('products'); // JSON column to store order items
            $table->string('payment_intent_id');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->json('shipping'); // JSON column for shipping details
            $table->string('payment_status');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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

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
        Schema::create('order_discounts', function (Blueprint $table) {
            $table->id('order_discount_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('discount_id');
            $table->timestamp('applied_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->foreign('order_id')->references('order_id')->on('orders');
            $table->foreign('discount_id')->references('discount_id')->on('discount_codes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_discounts');
    }
};

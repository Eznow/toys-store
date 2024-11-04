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
        Schema::table('complaints', function (Blueprint $table) {
            $table->foreign('order_id')
                ->references('order_id')
                ->on('orders')
                ->onDelete('cascade')
                ->name('fk_complaints_order_id');
            
            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade')
                ->name('fk_complaints_user_id');
        });
    }
    
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropForeign('fk_complaints_order_id');
            $table->dropForeign('fk_complaints_user_id');
        });
    }
    
};

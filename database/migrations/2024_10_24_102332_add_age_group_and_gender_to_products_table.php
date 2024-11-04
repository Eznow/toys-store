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
        Schema::table('products', function (Blueprint $table) {
            $table->string('age_group')->nullable(); // Nhóm tuổi như "6-12", "3-5", v.v.
            $table->enum('gender', ['male', 'female', 'unisex'])->nullable(); // Giới tính
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('age_group');
            $table->dropColumn('gender');
        });
    }
};

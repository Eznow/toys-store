<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reply_media', function (Blueprint $table) {
            $table->id('media_id');
            $table->unsignedBigInteger('reply_id');
            $table->string('file_path');
            $table->timestamps();

            // Thêm ràng buộc khóa ngoại
            $table->foreign('reply_id')->references('reply_id')->on('complaint_replies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reply_media');
    }
};

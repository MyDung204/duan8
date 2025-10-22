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
        // Bắt buộc: Tắt kiểm tra khóa ngoại
        Schema::disableForeignKeyConstraints();

        // Xóa cái bảng đang gây rối
        Schema::dropIfExists('image_categories_new');

        // Bật lại kiểm tra khóa ngoại
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

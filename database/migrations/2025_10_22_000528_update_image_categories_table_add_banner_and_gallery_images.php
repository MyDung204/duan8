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
        Schema::table('image_categories', function (Blueprint $table) {
            // Đổi tên cột image thành banner_image
            $table->renameColumn('image', 'banner_image');
            
            // Thêm cột gallery_images để lưu JSON array các ảnh trong thư viện
            $table->json('gallery_images')->nullable()->after('banner_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('image_categories', function (Blueprint $table) {
            // Đổi tên lại cột banner_image thành image
            $table->renameColumn('banner_image', 'image');
            
            // Xóa cột gallery_images
            $table->dropColumn('gallery_images');
        });
    }
};
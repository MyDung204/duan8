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
        // Cập nhật foreign key constraint trong bảng posts
        Schema::table('posts', function (Blueprint $table) {
            // Xóa foreign key cũ
            $table->dropForeign(['category_id']);
            
            // Thêm foreign key mới trỏ đến bảng categories
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
        
        // Cập nhật foreign key constraint trong bảng categories (self-reference)
        Schema::table('categories', function (Blueprint $table) {
            // Xóa foreign key cũ với tên cũ
            $table->dropForeign('image_categories_parent_id_foreign');
            
            // Thêm foreign key mới trỏ đến chính bảng categories
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hoàn tác foreign key constraints
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->foreign('category_id')->references('id')->on('image_categories')->onDelete('set null');
        });
        
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->foreign('parent_id')->references('id')->on('image_categories')->onDelete('cascade');
        });
    }
};

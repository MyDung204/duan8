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
        Schema::create('image_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tiêu đề danh mục (required)
            $table->string('slug')->unique(); // URL-friendly slug (unique)
            $table->text('short_description')->nullable(); // Mô tả ngắn (nullable)
            $table->longText('content')->nullable(); // Nội dung chi tiết (nullable)
            $table->string('author_name')->nullable(); // Tên tác giả (nullable)
            $table->string('image')->nullable(); // Đường dẫn ảnh (nullable)
            $table->foreignId('parent_id')->nullable()->constrained('image_categories')->onDelete('cascade'); // Foreign key cho cấu trúc cha-con
            $table->integer('order')->default(0); // Thứ tự sắp xếp
            $table->boolean('is_active')->default(true); // Trạng thái kích hoạt
            $table->timestamps();
            
            // Indexes để tối ưu performance
            $table->index(['parent_id', 'order']);
            $table->index(['is_active', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    // Thêm 2 dòng này để tắt/bật kiểm tra khóa ngoại
    Schema::disableForeignKeyConstraints();

    Schema::dropIfExists('image_categories');

    Schema::enableForeignKeyConstraints();
}
};

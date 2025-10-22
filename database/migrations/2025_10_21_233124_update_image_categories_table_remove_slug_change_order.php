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
        // SQLite không hỗ trợ drop column trực tiếp, cần tạo lại bảng
        Schema::create('image_categories_new', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Tiêu đề danh mục (required)
            $table->text('short_description')->nullable(); // Mô tả ngắn (nullable)
            $table->longText('content')->nullable(); // Nội dung chi tiết (nullable)
            $table->string('author_name')->nullable(); // Tên tác giả (nullable)
            $table->string('image')->nullable(); // Đường dẫn ảnh (nullable)
            $table->foreignId('parent_id')->nullable()->constrained('image_categories')->onDelete('cascade'); // Foreign key cho cấu trúc cha-con
            $table->integer('order')->default(0); // Thứ tự sắp xếp (sẽ được set tự động)
            $table->boolean('is_active')->default(true); // Trạng thái kích hoạt
            $table->timestamps();
            
            // Indexes để tối ưu performance
            $table->index(['parent_id', 'order']);
            $table->index(['is_active', 'order']);
        });

        // Copy dữ liệu từ bảng cũ sang bảng mới
        DB::statement('INSERT INTO image_categories_new (id, title, short_description, content, author_name, image, parent_id, is_active, created_at, updated_at) SELECT id, title, short_description, content, author_name, image, parent_id, is_active, created_at, updated_at FROM image_categories');

        // Xóa bảng cũ và đổi tên bảng mới
        Schema::dropIfExists('image_categories');
        Schema::rename('image_categories_new', 'image_categories');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('image_categories_old', function (Blueprint $table) {
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

        // Copy dữ liệu từ bảng hiện tại sang bảng cũ
        DB::statement('INSERT INTO image_categories_old (id, title, short_description, content, author_name, image, parent_id, is_active, created_at, updated_at) SELECT id, title, short_description, content, author_name, image, parent_id, is_active, created_at, updated_at FROM image_categories');

        // Xóa bảng hiện tại và đổi tên bảng cũ
        Schema::dropIfExists('image_categories');
        Schema::rename('image_categories_old', 'image_categories');
    }
};
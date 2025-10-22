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
        Schema::create('posts', function (Blueprint $table) {
           $table->id();
            $table->string('title'); // Tiêu đề bài viết
            $table->foreignId('image_category_id')->nullable()->constrained('image_categories')->onDelete('set null'); // Liên kết tới danh mục (có thể không có)
            $table->text('content')->nullable(); // Nội dung bài viết
            $table->string('author_name')->nullable(); // Tên tác giả
            $table->string('banner_image')->nullable(); // Ảnh banner
            $table->boolean('is_active')->default(true); // Trạng thái
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

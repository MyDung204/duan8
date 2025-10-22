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
        // BẮT BUỘC: Tắt kiểm tra khóa ngoại
        Schema::disableForeignKeyConstraints();

        // Tạo bảng mới
        Schema::create('image_categories_new', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('short_description')->nullable();
            $table->longText('content')->nullable();
            $table->string('author_name')->nullable();
            $table->string('image')->nullable();
            
            // QUAN TRỌNG: Không tạo foreign key ở đây vội
            $table->foreignId('parent_id')->nullable(); 
            
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['parent_id', 'order']);
            $table->index(['is_active', 'order']);
        });

        // Copy dữ liệu
        DB::statement('INSERT INTO image_categories_new (id, title, short_description, content, author_name, image, parent_id, is_active, created_at, updated_at) SELECT id, title, short_description, content, author_name, image, parent_id, is_active, created_at, updated_at FROM image_categories');

        // Xóa bảng cũ và đổi tên bảng mới
        Schema::dropIfExists('image_categories');
        Schema::rename('image_categories_new', 'image_categories');

        // SỬA: Tạo lại Foreign Key sau khi đã đổi tên
        Schema::table('image_categories', function (Blueprint $table) {
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('image_categories')
                  ->onDelete('cascade');
        });

        // BẬT LẠI: Luôn bật lại ở cuối
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // BẮT BUỘC: Tắt kiểm tra khóa ngoại
        Schema::disableForeignKeyConstraints();

        Schema::create('image_categories_old', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique(); // Thêm lại cột slug
            $table->text('short_description')->nullable();
            $table->longText('content')->nullable();
            $table->string('author_name')->nullable();
            $table->string('image')->nullable();
            
            // QUAN TRỌNG: Không tạo foreign key ở đây
            $table->foreignId('parent_id')->nullable();
            
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['parent_id', 'order']);
            $table->index(['is_active', 'order']);
        });

        // Copy dữ liệu (thêm `slug` tạm)
        DB::statement('INSERT INTO image_categories_old (id, title, slug, short_description, content, author_name, image, parent_id, is_active, created_at, updated_at) SELECT id, title, id, short_description, content, author_name, image, parent_id, is_active, created_at, updated_at FROM image_categories');

        // Xóa bảng hiện tại và đổi tên bảng cũ
        Schema::dropIfExists('image_categories');
        Schema::rename('image_categories_old', 'image_categories');

        // SỬA: Tạo lại Foreign Key sau khi đã đổi tên
        Schema::table('image_categories', function (Blueprint $table) {
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('image_categories')
                  ->onDelete('cascade');
        });

        // BẬT LẠI: Luôn bật lại ở cuối
        Schema::enableForeignKeyConstraints();
    }
};
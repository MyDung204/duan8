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
        Schema::table('posts', function (Blueprint $table) {
            // Thêm các cột cần thiết nếu chưa có
            if (!Schema::hasColumn('posts', 'banner_image')) {
                $table->string('banner_image')->nullable()->after('content');
            }
            
            if (!Schema::hasColumn('posts', 'gallery_images')) {
                $table->json('gallery_images')->nullable()->after('banner_image');
            }
            
            if (!Schema::hasColumn('posts', 'author_name')) {
                $table->string('author_name')->nullable()->after('gallery_images');
            }
            
            if (!Schema::hasColumn('posts', 'category_id')) {
                $table->foreignId('category_id')->nullable()->constrained('image_categories')->onDelete('set null')->after('author_name');
            }
            
            if (!Schema::hasColumn('posts', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('category_id');
            }
            
            if (!Schema::hasColumn('posts', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('is_published');
            }
        });
        
        // Thêm indexes nếu chưa có
        Schema::table('posts', function (Blueprint $table) {
            $table->index(['is_published', 'published_at'], 'posts_published_index');
            $table->index('category_id', 'posts_category_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_published_index');
            $table->dropIndex('posts_category_index');
            
            $table->dropColumn([
                'banner_image',
                'gallery_images', 
                'author_name',
                'category_id',
                'is_published',
                'published_at'
            ]);
        });
    }
};
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ImageCategory;

class ImageCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo danh mục cha
        $parent1 = ImageCategory::create([
            'title' => 'Du lịch',
            'short_description' => 'Các danh mục về du lịch và khám phá',
            'content' => 'Nội dung chi tiết về du lịch...',
            'author_name' => 'Admin',
            'banner_image' => null,
            'parent_id' => null,
            'is_active' => true,
        ]);

        $parent2 = ImageCategory::create([
            'title' => 'Ẩm thực',
            'short_description' => 'Các món ăn và thức uống',
            'content' => 'Nội dung chi tiết về ẩm thực...',
            'author_name' => 'Admin',
            'banner_image' => null,
            'parent_id' => null,
            'is_active' => true,
        ]);

        $parent3 = ImageCategory::create([
            'title' => 'Thể thao',
            'short_description' => 'Các hoạt động thể thao',
            'content' => 'Nội dung chi tiết về thể thao...',
            'author_name' => 'Admin',
            'banner_image' => null,
            'parent_id' => null,
            'is_active' => true,
        ]);

        // Tạo danh mục con cho Du lịch
        ImageCategory::create([
            'title' => 'Du lịch trong nước',
            'short_description' => 'Các điểm du lịch trong nước',
            'content' => 'Nội dung về du lịch trong nước...',
            'author_name' => 'Admin',
            'banner_image' => null,
            'parent_id' => $parent1->id,
            'is_active' => true,
        ]);

        ImageCategory::create([
            'title' => 'Du lịch nước ngoài',
            'short_description' => 'Các điểm du lịch quốc tế',
            'content' => 'Nội dung về du lịch nước ngoài...',
            'author_name' => 'Admin',
            'banner_image' => null,
            'parent_id' => $parent1->id,
            'is_active' => true,
        ]);

        // Tạo danh mục con cho Ẩm thực
        ImageCategory::create([
            'title' => 'Món ăn Việt Nam',
            'short_description' => 'Các món ăn truyền thống Việt Nam',
            'content' => 'Nội dung về món ăn Việt Nam...',
            'author_name' => 'Admin',
            'banner_image' => null,
            'parent_id' => $parent2->id,
            'is_active' => true,
        ]);

        ImageCategory::create([
            'title' => 'Món ăn quốc tế',
            'short_description' => 'Các món ăn từ các nước khác',
            'content' => 'Nội dung về món ăn quốc tế...',
            'author_name' => 'Admin',
            'banner_image' => null,
            'parent_id' => $parent2->id,
            'is_active' => true,
        ]);

        // Tạo danh mục con cho Thể thao
        ImageCategory::create([
            'title' => 'Bóng đá',
            'short_description' => 'Tin tức và thông tin về bóng đá',
            'content' => 'Nội dung về bóng đá...',
            'author_name' => 'Admin',
            'banner_image' => null,
            'parent_id' => $parent3->id,
            'is_active' => true,
        ]);

        ImageCategory::create([
            'title' => 'Bóng rổ',
            'short_description' => 'Tin tức và thông tin về bóng rổ',
            'content' => 'Nội dung về bóng rổ...',
            'author_name' => 'Admin',
            'banner_image' => null,
            'parent_id' => $parent3->id,
            'is_active' => true,
        ]);

        // Tạo thêm một số danh mục để test tìm kiếm
        ImageCategory::create([
            'title' => 'Công nghệ',
            'short_description' => 'Tin tức công nghệ mới nhất',
            'content' => 'Nội dung về công nghệ...',
            'author_name' => 'Tech Admin',
            'banner_image' => null,
            'parent_id' => null,
            'is_active' => true,
        ]);

        ImageCategory::create([
            'title' => 'Giáo dục',
            'short_description' => 'Thông tin giáo dục và học tập',
            'content' => 'Nội dung về giáo dục...',
            'author_name' => 'Education Admin',
            'banner_image' => null,
            'parent_id' => null,
            'is_active' => true,
        ]);

        // Tạo một danh mục không active để test
        ImageCategory::create([
            'title' => 'Danh mục ẩn',
            'short_description' => 'Danh mục này đã bị ẩn',
            'content' => 'Nội dung danh mục ẩn...',
            'author_name' => 'Admin',
            'banner_image' => null,
            'parent_id' => null,
            'is_active' => false,
        ]);
    }
}


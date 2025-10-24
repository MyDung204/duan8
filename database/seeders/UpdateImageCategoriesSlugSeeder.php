<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class UpdateImageCategoriesSlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy tất cả danh mục chưa có slug
        $categories = Category::whereNull('slug')->get();
        
        foreach ($categories as $category) {
            // Tạo slug từ title
            $baseSlug = Str::slug($category->title);
            $slug = $baseSlug;
            $counter = 1;
            
            // Kiểm tra slug có trùng không, nếu trùng thì thêm số
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            
            // Cập nhật slug
            $category->update(['slug' => $slug]);
            
            echo "Updated category '{$category->title}' with slug '{$slug}'\n";
        }
        
        echo "Completed updating slugs for " . $categories->count() . " categories.\n";
    }
}

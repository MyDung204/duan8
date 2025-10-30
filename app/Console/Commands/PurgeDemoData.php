<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PurgeDemoData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * --force: bắt buộc để chạy
     * --remove-demo-users: xóa user test rõ ràng (mặc định có)
     * --remove-demo-categories: xóa danh mục demo an toàn (leaf, không có bài)
     */
    protected $signature = 'app:purge-demo-data {--force} {--remove-demo-users} {--remove-demo-categories}';

	/**
	 * The console command description.
	 */
    protected $description = 'Xóa dữ liệu ảo/sai/dư thừa một cách an toàn (không đụng dữ liệu thật)';

	public function handle(): int
	{
		if (!$this->option('force')) {
			$this->error('Vui lòng chạy lại kèm --force để xác nhận.');
			return self::FAILURE;
		}

        DB::beginTransaction();
        try {
            $deletedUsers = 0;
            $deletedCategories = 0;

            // Xóa người dùng test rõ ràng
            if ($this->option('remove-demo-users')) {
                $deletedUsers = DB::table('users')
                    ->whereIn('email', ['test@example.com', 'admin@example.com'])
                    ->orWhere('email', 'like', '%@example.com')
                    ->orWhere('email', 'like', '%@test.com')
                    ->orWhere('name', 'Test User')
                    ->delete();
            }

            // Xóa danh mục demo an toàn (chỉ leaf, không có bài viết)
            if ($this->option('remove-demo-categories')) {
                $demoTitles = [
                    'Du lịch','Ẩm thực','Thể thao',
                    'Du lịch trong nước','Du lịch nước ngoài',
                    'Món ăn Việt Nam','Món ăn quốc tế',
                    'Bóng đá','Bóng rổ',
                    'Công nghệ','Giáo dục','Danh mục ẩn',
                ];
                $demoAuthors = ['Admin','Tech Admin','Education Admin'];

                // Xóa các leaf category (không có con, không có bài) trước
                $leafIds = DB::table('categories as c')
                    ->leftJoin('categories as ch', 'ch.parent_id', '=', 'c.id')
                    ->leftJoin('posts as p', 'p.category_id', '=', 'c.id')
                    ->whereNull('ch.id')
                    ->whereNull('p.id')
                    ->whereIn('c.title', $demoTitles)
                    ->whereIn('c.author_name', $demoAuthors)
                    ->pluck('c.id')->toArray();

                if (!empty($leafIds)) {
                    $deletedCategories += DB::table('categories')->whereIn('id', $leafIds)->delete();
                }

                // Tiếp tục xóa các parent demo nếu đã trở thành leaf và không có bài sau lần xóa đầu
                $parentIds = DB::table('categories as c')
                    ->leftJoin('categories as ch', 'ch.parent_id', '=', 'c.id')
                    ->leftJoin('posts as p', 'p.category_id', '=', 'c.id')
                    ->whereNull('ch.id')
                    ->whereNull('p.id')
                    ->whereIn('c.title', ['Du lịch','Ẩm thực','Thể thao'])
                    ->whereIn('c.author_name', $demoAuthors)
                    ->pluck('c.id')->toArray();

                if (!empty($parentIds)) {
                    $deletedCategories += DB::table('categories')->whereIn('id', $parentIds)->delete();
                }
            }

            DB::commit();
            $this->info('Đã xóa dữ liệu demo an toàn. Users xóa: ' . $deletedUsers . ', Categories xóa: ' . $deletedCategories);
            return self::SUCCESS;
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error('Lỗi khi xóa dữ liệu: ' . $e->getMessage());
            return self::FAILURE;
        }
	}
}



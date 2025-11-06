<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class RegenerateCategoryImages extends Command
{
    protected $signature = 'app:regenerate-category-images';
    protected $description = 'Regenerate responsive image variants (small, medium, large) for existing categories.';

    public function handle()
    {
        $this->info('Starting to regenerate category images...');

        // Manually instantiate the ImageManager with the GD driver
        $imageManager = new ImageManager(new Driver());

        $categories = Category::whereNotNull('banner_image')->get();
        $progressBar = $this->output->createProgressBar($categories->count());
        $progressBar->start();

        $sizes = ['large' => 1200, 'medium' => 800, 'small' => 480];

        foreach ($categories as $category) {
            $originalPath = $category->banner_image;

            if (!Storage::disk('public')->exists($originalPath)) {
                $this->warn("  Original image not found for category ID {$category->id}: {$originalPath}");
                $progressBar->advance();
                continue;
            }

            $originalImageFullPath = Storage::disk('public')->path($originalPath);
            $path_info = pathinfo($originalPath);
            $directory = $path_info['dirname'];
            $filename = $path_info['filename'];
            $extension = $path_info['extension'];

            foreach ($sizes as $name => $width) {
                $newFilename = "{$filename}-{$name}.{$extension}";
                $newPath = ($directory !== '.' ? $directory . '/' : '') . $newFilename;

                if (Storage::disk('public')->exists($newPath)) {
                    continue;
                }

                try {
                    $image = $imageManager->read($originalImageFullPath);
                    $image->scaleDown(width: $width);
                    $image->save(Storage::disk('public')->path($newPath));
                } catch (\Exception $e) {
                    $this->error("  Failed to resize image for category ID {$category->id}: {$originalPath} to size {$name}. Error: " . $e->getMessage());
                    Log::error("Image resizing failed in command for category ID {$category->id}: " . $e->getMessage());
                }
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->info("\nCategory image regeneration complete!");

        return 0;
    }
}

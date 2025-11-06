<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class RegeneratePostImages extends Command
{
    protected $signature = 'app:regenerate-post-images';
    protected $description = 'Regenerate responsive image variants (small, medium, large) for existing posts.';

    public function handle()
    {
        $this->info('Starting to regenerate post images...');

        // Manually instantiate the ImageManager with the GD driver
        $imageManager = new ImageManager(new Driver());

        $posts = Post::whereNotNull('banner_image')->get();
        $progressBar = $this->output->createProgressBar($posts->count());
        $progressBar->start();

        $sizes = ['large' => 1200, 'medium' => 800, 'small' => 480];

        foreach ($posts as $post) {
            $baseFilename = $post->banner_image;
            $originalPath = 'posts/banners/' . $baseFilename;

            if (!Storage::disk('public')->exists($originalPath)) {
                $this->warn("  Original image not found for post ID {$post->id}: {$baseFilename}");
                $progressBar->advance();
                continue;
            }

            $originalImageFullPath = Storage::disk('public')->path($originalPath);
            $path_info = pathinfo($baseFilename);
            $filename = $path_info['filename'];
            $extension = $path_info['extension'];

            foreach ($sizes as $name => $width) {
                $newFilename = "{$filename}-{$name}.{$extension}";
                $newPath = 'posts/banners/' . $newFilename;

                if (Storage::disk('public')->exists($newPath)) {
                    continue;
                }

                try {
                    $image = $imageManager->read($originalImageFullPath);
                    $image->scaleDown(width: $width);
                    $image->save(Storage::disk('public')->path($newPath));
                } catch (\Exception $e) {
                    $this->error("  Failed to resize image for post ID {$post->id}: {$baseFilename} to size {$name}. Error: " . $e->getMessage());
                    Log::error("Image resizing failed in command for post ID {$post->id}: " . $e->getMessage());
                }
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->info("\nImage regeneration complete!");

        return 0;
    }
}

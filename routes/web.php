<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Models\Post;
use App\Models\Category;

Route::get('/', function () {
    $latestPosts = Post::published()
        ->with('category')
        ->latest()
        ->take(6)
        ->get();
    $topCategories = Category::active()
        ->roots()
        ->withCount(['posts' => function ($query) {
            $query->published();
        }])
        ->orderBy('title')
        ->get();
    return view('frontend.home', compact('latestPosts', 'topCategories'));
})->name('home');

Route::get('/bai-viet/{post:slug}', function (Post $post) {
    // Increment view count
    $post->increment('views_count');
    $post->refresh();
    
    // Get related posts
    $relatedPosts = collect();
    if ($post->category) {
        $relatedPosts = $post->category->posts()
            ->published()
            ->where('id', '!=', $post->id)
            ->with('category')
            ->latest()
            ->take(6)
            ->get();
    }
    
    // Get previous and next posts
    $previousPost = Post::published()
        ->where('id', '<', $post->id)
        ->latest('id')
        ->first();
        
    $nextPost = Post::published()
        ->where('id', '>', $post->id)
        ->oldest('id')
        ->first();
    
    return view('frontend.posts.show', compact('post', 'relatedPosts', 'previousPost', 'nextPost'));
})->name('posts.show.public');

// Public: Posts
Route::get('/bai-viet', function () {
    $query = Post::published()->latest();
    if (request('q')) {
        $query->search(request('q'));
    }
    if (request('category')) {
        $query->byCategory((int) request('category'));
    }
    $posts = $query->paginate(9)->withQueryString();
    $categories = Category::active()->orderBy('title')->get();
    return view('frontend.posts.index', compact('posts', 'categories'));
})->name('posts.public');

// Public: Categories
Route::get('/danh-muc', function () {
    $categories = Category::active()->roots()->with(['children' => function($q){
        $q->active()->orderBy('title');
    }])->orderBy('title')->get();
    return view('frontend.categories.index', compact('categories'));
})->name('categories.public');

// Public: Category Detail
Route::get('/danh-muc/{category}', function ($identifier) {
    // Try to find category by slug first, then by id
    $category = Category::where(function($query) use ($identifier) {
        $query->where('slug', $identifier);
        if (is_numeric($identifier)) {
            $query->orWhere('id', $identifier);
        }
    })->firstOrFail();
    
    $category->load(['parent', 'children' => function($q) {
        $q->active()->orderBy('title');
    }]);
    
    // Get all posts in this category (including subcategories)
    $categoryIds = [$category->id];
    
    // Include posts from subcategories
    if ($category->children->count() > 0) {
        $categoryIds = array_merge($categoryIds, $category->children->pluck('id')->toArray());
    }
    
    $postsQuery = Post::published()
        ->whereIn('category_id', $categoryIds);
    
    // Calculate stats (including subcategories)
    $totalPosts = Post::published()->whereIn('category_id', $categoryIds)->count();
    $totalViews = Post::published()->whereIn('category_id', $categoryIds)->sum('views_count');
    
    // Get featured/latest posts (first 3) - including subcategories
    $featuredPosts = Post::published()
        ->whereIn('category_id', $categoryIds)
        ->with('category')
        ->latest()
        ->take(3)
        ->get();
    
    $posts = $postsQuery->with('category')
        ->latest()
        ->paginate(12)
        ->withQueryString();
    
    // Get related categories (sibling categories)
    $relatedCategories = Category::active()
        ->where('id', '!=', $category->id)
        ->where('parent_id', $category->parent_id)
        ->take(6)
        ->get();
    
    // Get breadcrumbs
    $breadcrumbs = [];
    $current = $category;
    while ($current) {
        array_unshift($breadcrumbs, $current);
        $current = $current->parent;
    }
    
    return view('frontend.categories.show', compact(
        'category', 
        'posts', 
        'relatedCategories',
        'featuredPosts',
        'totalPosts',
        'totalViews',
        'breadcrumbs'
    ));
})->name('categories.show.public');

// Public: About
Route::view('/ve-chung-toi', 'frontend.about')->name('about');

// Public: Contact
Route::view('/lien-he', 'frontend.contact')->name('contact');

Volt::route('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/api/categories/{category}/posts', function (Category $category) {
    try {
        $posts = $category->posts()
            ->published()
            ->with('category')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'slug' => $post->slug,
                    'short_description' => $post->short_description,
                    'banner_image_url' => $post->banner_image_url,
                    'created_date' => $post->created_date,
                    'author_name' => $post->author_name,
                    'views_count' => $post->views_count,
                    'category' => $post->category ? [
                        'id' => $post->category->id,
                        'title' => $post->category->title,
                        'slug' => $post->category->slug,
                    ] : null,
                ];
            });
        return response()->json($posts);
    } catch (\Exception $e) {
        // Log the error for server-side debugging
        Log::error("Error fetching posts for category {$category->id}: " . $e->getMessage());
        return response()->json(['error' => 'Failed to fetch posts', 'details' => $e->getMessage()], 500);
    }
})->name('api.categories.posts');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('categories', 'categories.index')->name('categories.index');
    Volt::route('categories/create', 'categories.form')->name('categories.create');
    Volt::route('categories/edit/{id}', 'categories.form')->name('categories.edit');
    
    // Posts Routes
    Volt::route('posts', 'posts.index')->name('posts.index');
    Volt::route('posts/create', 'posts.form')->name('posts.create'); 
    Volt::route('posts/edit/{id}', 'posts.form')->name('posts.edit');
    Volt::route('posts/show/{id}', 'posts.show')->name('posts.show');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});




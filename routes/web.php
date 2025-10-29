<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Models\Post;
use App\Models\Category;

Route::get('/', function () {
    $latestPosts = Post::published()->latest()->take(6)->get();
    $topCategories = Category::active()->roots()->take(8)->get();
    return view('frontend.home', compact('latestPosts', 'topCategories'));
})->name('home');

Route::get('/bai-viet/{post:slug}', function (Post $post) {
    return view('frontend.posts.show', compact('post'));
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

// Public: About
Route::view('/ve-chung-toi', 'frontend.about')->name('about');

// Public: Contact
Route::view('/lien-he', 'frontend.contact')->name('contact');

Volt::route('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/api/categories/{category}/posts', function (Category $category) {
    try {
        $posts = $category->posts()->published()->latest()->take(3)->get();
        return response()->json($posts);
    } catch (\Exception $e) {
        // Log the error for server-side debugging
        \Log::error("Error fetching posts for category {$category->id}: " . $e->getMessage());
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




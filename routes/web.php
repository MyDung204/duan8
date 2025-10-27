<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Volt::route('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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




<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    Route::view('/', 'admin.dashboard')
        ->middleware(['verified'])
        ->name('dashboard');

    Route::view('profile', 'admin.profile')
        ->name('profile');

    // Roles CRUD
    Route::middleware('can:menu.roles')->group(function () {
        Route::get('roles', \App\Livewire\Admin\Roles\RoleIndex::class)
            ->name('roles.index');
        Route::get('roles/create', \App\Livewire\Admin\Roles\RoleForm::class)
            ->name('roles.create');
        Route::get('roles/{roleId}/edit', \App\Livewire\Admin\Roles\RoleForm::class)
            ->name('roles.edit');
    });

    // Users CRUD
    Route::middleware('can:menu.users')->group(function () {
        Route::get('users', \App\Livewire\Admin\Users\UserIndex::class)
            ->name('users.index');
        Route::get('users/create', \App\Livewire\Admin\Users\UserForm::class)
            ->name('users.create');
        Route::get('users/{userId}/edit', \App\Livewire\Admin\Users\UserForm::class)
            ->name('users.edit');
    });

    // Test Products CRUD
    Route::middleware('can:menu.test-template')->group(function () {
        Route::get('test-products', \App\Livewire\Admin\TestProducts\ProductIndex::class)
            ->name('test-products.index');
        Route::get('test-products/create', \App\Livewire\Admin\TestProducts\ProductForm::class)
            ->name('test-products.create');
        Route::get('test-products/{productId}/edit', \App\Livewire\Admin\TestProducts\ProductForm::class)
            ->name('test-products.edit');
    });

    // Events CRUD
    Route::middleware('can:menu.events')->group(function () {
        Route::get('events', \App\Livewire\Admin\Events\EventIndex::class)
            ->name('events.index');
        Route::get('events/create', \App\Livewire\Admin\Events\EventForm::class)
            ->name('events.create');
        Route::get('events/{participantId}/edit', \App\Livewire\Admin\Events\EventForm::class)
            ->name('events.edit');
    });
});

require __DIR__ . '/auth.php';

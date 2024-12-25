<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
});

Route::middleware(['auth', AuthAdmin::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brands/add', [AdminController::class, 'add_brand'])->name('admin.brand-add');
    Route::post('/admin/brands/store', [AdminController::class, 'brand_store'])->name('admin.brand.store');
    // Route to show the edit brand form
    Route::get('/admin/brands/{id}/edit', [AdminController::class, 'edit'])->name('admin.brand.edit');
    // Route to handle the update form submission
    Route::put('/admin/brands/{id}', [AdminController::class, 'update'])->name('admin.brand.update');
    Route::delete('/admin/brands/{id}', [AdminController::class, 'destroy'])->name('admin.brand.delete');

    // Category Routes
    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/categories/add', [AdminController::class, 'add_category'])->name('admin.category.add');
    Route::post('/admin/categories/store', [AdminController::class, 'store_category'])->name('admin.category.store');
    Route::get('/admin/categories/{id}/edit', [AdminController::class, 'edit_category'])->name('admin.category.edit');
    Route::put('/admin/categories/{id}', [AdminController::class, 'update_category'])->name('admin.category.update');
    Route::delete('/admin/categories/{id}', [AdminController::class, 'destroy_category'])->name('admin.category.delete');
});
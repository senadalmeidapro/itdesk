<?php

use App\Livewire\Admin\CategoryManager;
use App\Livewire\Admin\DepartmentManager;
use App\Livewire\Admin\SlaPolicyManager;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/departments', DepartmentManager::class)->name('departments');
    Route::get('/categories', CategoryManager::class)->name('categories');
    Route::get('/sla-policies', SlaPolicyManager::class)->name('sla-policies');
});

<?php

use App\Livewire\Assets\AssetCreate;
use App\Livewire\Assets\AssetEdit;
use App\Livewire\Assets\AssetIndex;
use App\Livewire\Assets\AssetShow;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/assets', AssetIndex::class)->name('assets.index');
    Route::get('/assets/create', AssetCreate::class)->name('assets.create');
    Route::get('/assets/{asset}/edit', AssetEdit::class)->name('assets.edit');
    Route::get('/assets/{asset}', AssetShow::class)->name('assets.show');
});

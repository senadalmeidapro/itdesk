<?php

use App\Livewire\Assets\MyAssetIndex;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/my-assets', MyAssetIndex::class)->name('assets.mine');
});
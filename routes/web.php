<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/web_admin.php';
require __DIR__.'/web_assets.php';
require __DIR__.'/web_attachments.php';
require __DIR__.'/web_dashboard.php';
require __DIR__.'/web_my_assets.php';
require __DIR__.'/web_tickets.php';
require __DIR__.'/settings.php';

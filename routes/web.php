<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/services', 'services')->name('services');
Route::view('/contact', 'contact')->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

require __DIR__.'/web_assets.php';
require __DIR__.'/web_attachments.php';
require __DIR__.'/web_dashboard.php';
require __DIR__.'/web_my_assets.php';
require __DIR__.'/web_tickets.php';
require __DIR__.'/settings.php';

<?php

use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->get('/dashboard', Dashboard::class)->name('dashboard');
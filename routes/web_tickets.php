<?php

use App\Livewire\Tickets\TicketCreate;
use App\Livewire\Tickets\TicketIndex;
use App\Livewire\Tickets\TicketShow;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/tickets', TicketIndex::class)->name('tickets.index');
    Route::get('/tickets/create', TicketCreate::class)->name('tickets.create');
    Route::get('/tickets/{ticket}', TicketShow::class)->name('tickets.show');
});
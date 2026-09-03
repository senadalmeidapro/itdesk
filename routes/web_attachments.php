<?php

use App\Http\Controllers\TicketAttachmentDownloadController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/attachments/{attachment}/download', TicketAttachmentDownloadController::class)
        ->name('attachments.download');
});
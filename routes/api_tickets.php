<?php

use App\Http\Controllers\Api\TicketCommentController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tickets', TicketController::class);
    Route::post('tickets/{ticket}/transition', [TicketController::class, 'transition']);
    Route::post('tickets/{ticket}/comments', [TicketCommentController::class, 'store']);
});
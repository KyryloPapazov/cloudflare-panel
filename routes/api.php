<?php

use App\Http\Controllers\Cloudflare\CloudflareAccountController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cloudflare-accounts', [CloudflareAccountController::class, 'index']);
    Route::post('/cloudflare-accounts', [CloudflareAccountController::class, 'store']);
    Route::get('/cloudflare-accounts/{id}', [CloudflareAccountController::class, 'show']);
    Route::delete('/cloudflare-accounts/{id}', [CloudflareAccountController::class, 'destroy']);
});

<?php

use App\Http\Controllers\Cloudflare\CloudflareAccountController;
use App\Http\Controllers\Cloudflare\DomainController;
use App\Http\Controllers\Cloudflare\PageRuleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    // Маршрут для отображения списка аккаунтов
    Route::get('/cloudflare-accounts', [CloudflareAccountController::class, 'index'])
        ->name('cloudflare-accounts.index');
    // Маршрут для отображения формы создания нового аккаунта
    Route::get('/cloudflare-accounts/create', [CloudflareAccountController::class, 'create'])
        ->name('cloudflare-accounts.create');
    // Маршрут для сохранения нового аккаунта
    Route::post('/cloudflare-accounts', [CloudflareAccountController::class, 'store'])
        ->name('cloudflare-accounts.store');
    // Маршрут для отображения деталей конкретного аккаунта
    Route::get('/cloudflare-accounts/{id}', [CloudflareAccountController::class, 'show'])
        ->name('cloudflare-accounts.show');
    // Маршрут для отображения формы редактирования аккаунта
    Route::get('/cloudflare-accounts/{id}/edit', [CloudflareAccountController::class, 'edit'])
        ->name('cloudflare-accounts.edit');
    // Маршрут для обновления данных аккаунта
    Route::put('/cloudflare-accounts/{id}', [CloudflareAccountController::class, 'update'])
        ->name('cloudflare-accounts.update');
    // Маршрут для удаления аккаунта
    Route::delete('/cloudflare-accounts/{id}', [CloudflareAccountController::class, 'destroy'])
        ->name('cloudflare-accounts.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('domains/{id}', [DomainController::class, 'index'])->name('cloudflare-domains.index');
    Route::get('domains/{id}/create', [DomainController::class, 'create'])->name('cloudflare-domains.create');
    Route::post('domains/store', [DomainController::class, 'store'])->name('cloudflare-domains.store');
    Route::get('domains/{id}/edit', [DomainController::class, 'edit'])->name('cloudflare-domains.edit');
    Route::delete('domains/{id}/destroy', [DomainController::class, 'destroy'])->name('cloudflare-domains.destroy');
});

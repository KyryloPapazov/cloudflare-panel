<?php

use App\Http\Controllers\Cloudflare\CloudflareAccountController;
use App\Http\Controllers\Cloudflare\DomainController;
use App\Http\Controllers\Cloudflare\PageRuleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/cloudflare-accounts', [CloudflareAccountController::class, 'index'])
        ->name('cloudflare-accounts.index');
    Route::get('/cloudflare-accounts/create', [CloudflareAccountController::class, 'create'])
        ->name('cloudflare-accounts.create');
    Route::post('/cloudflare-accounts', [CloudflareAccountController::class, 'store'])
        ->name('cloudflare-accounts.store');
//    Route::get('/cloudflare-accounts/{id}', [CloudflareAccountController::class, 'show'])
//        ->name('cloudflare-accounts.show');
    Route::get('/cloudflare-accounts/{id}/edit', [CloudflareAccountController::class, 'edit'])
        ->name('cloudflare-accounts.edit');
    Route::put('/cloudflare-accounts/{id}', [CloudflareAccountController::class, 'update'])
        ->name('cloudflare-accounts.update');
    Route::delete('/cloudflare-accounts/{id}', [CloudflareAccountController::class, 'destroy'])
        ->name('cloudflare-accounts.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('domains/{id}', [DomainController::class, 'index'])->name('cloudflare-domains.index');
    Route::get('domains/{id}/create', [DomainController::class, 'create'])->name('cloudflare-domains.create');
    Route::post('domains/store', [DomainController::class, 'store'])->name('cloudflare-domains.store');
//    Route::get('domains/{id}/edit', [DomainController::class, 'edit'])->name('cloudflare-domains.edit');
    Route::put('domains/{id}/update', [DomainController::class, 'update'])->name('cloudflare-domains.update');
    Route::delete('domains/{id}/destroy', [DomainController::class, 'destroy'])->name('cloudflare-domains.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('pagerules/{id}/index', [PageRuleController::class, 'index'])->name('domains.pagerules.index');
    Route::get('pagerules/{id}/create', [PageRuleController::class, 'create'])->name('domains.pagerules.create');
    Route::post('pagerules/{id}/store', [PageRuleController::class, 'store'])->name('domains.pagerules.store');
    Route::get('pagerules/{domainId}/{ruleId}/edit', [PageRuleController::class, 'edit'])->name('domains.pagerules.edit');
    Route::put('pagerules/{domainId}/{ruleId}/update', [PageRuleController::class, 'update'])
        ->name('domains.pagerules.update');
    Route::delete('pagerules/{pagerule}', [PageRuleController::class, 'destroy'])->name('pagerules.destroy');

});

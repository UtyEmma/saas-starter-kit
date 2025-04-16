<?php

use App\Features\SendApiRequestFeature;
use App\Http\Controllers\Billing\TransactionController;
use App\Http\Controllers\Settings\BillingController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::prefix('billing')->group(function(){
    Route::get('', [BillingController::class, 'index'])
        ->name('billing')
        ->can(SendApiRequestFeature::KEY);

    Route::prefix('transactions')->group(function(){
        Route::prefix('{transaction}')->group(function(){
            Route::get('verify', [TransactionController::class, 'verify'])->name('transaction.verify');
        });
    });

    Route::prefix('{planPrice}')->group(function(){
        Route::get('checkout', [SubscriptionController::class, 'checkout'])->name('billing.checkout');
        Route::get('trial', [SubscriptionController::class, 'startTrial'])->name('billing.trial');
    });
});

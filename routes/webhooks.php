<?php

use Illuminate\Support\Facades\Route;

Route::prefix('payments')->group(function(){
    Route::post('{gateway}', [\App\Http\Controllers\Webhooks\WebhookController::class, 'payment'])
        ->name('webhook.payment');
});

<?php

namespace App\Contracts\Payment;

use App\Support\HttpResponse;
use Illuminate\Http\Request;

interface HandlesWebhook {
    
    function handleWebhook(array $payload): HttpResponse;
    function verifyWebhook(Request $request): HttpResponse;
    
    
}
<?php

namespace App\Contracts\Payment;

use App\Support\HttpResponse;

interface HandlesWebhook {
    
    function handleWebhook(array $payload): HttpResponse;
    
    
}
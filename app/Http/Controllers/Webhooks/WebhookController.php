<?php

namespace App\Http\Controllers\Webhooks;

use App\Enums\PaymentGateways;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    
    function payment(Request $request, PaymentGateways $gateway) {
        $payload = $request->all();
        [$status, $message] = $gateway->provider()->webhook($payload);
        if(!$status) return response($message, 400);
        return response('Webhook received successfully');
    }
    
}

<?php

namespace App\PaymentGateways\Stripe\Concerns;

use Illuminate\Support\Facades\Request;

trait ManageWebhooks {
    
    function event(Request $request) {
        
    }

}
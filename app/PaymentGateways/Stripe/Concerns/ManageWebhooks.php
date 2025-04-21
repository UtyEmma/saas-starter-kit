<?php

namespace App\PaymentGateways\Stripe\Concerns;

use Illuminate\Support\Facades\Request;
use Stripe\Exception\SignatureVerificationException;
use Stripe\WebhookSignature;

trait ManageWebhooks {

    function verifyWebhookSignature(){
        $request = request();
        
        try {
            WebhookSignature::verifyHeader(
                $request->getContent(),
                $request->header('Stripe-Signature'),
                env('STRIPE_SECRET')
            );
        } catch (SignatureVerificationException $exception) {
            return state(false, $exception->getMessage(), $exception);
        }

        return state(true);
    }
    
    function handleWebhook(Request $request) {
        
    }

    

}
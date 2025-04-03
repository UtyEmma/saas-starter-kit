<?php

namespace App\Concerns\Payment;

use App\Models\Transaction;

trait HandlesRedirectPayment
{
    
    function callbackUrl(string $reference) {
        return route('', ['reference' => $reference]);
    }

    
}

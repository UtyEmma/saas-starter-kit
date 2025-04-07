<?php

namespace App\Contracts\Payment;

use App\Models\Transactions\Transaction;
use App\Support\HttpResponse;

interface HandlesCheckout {

    public function startCheckout(Transaction $transaction): HttpResponse;

    function getCheckoutId(mixed $response): string;

}
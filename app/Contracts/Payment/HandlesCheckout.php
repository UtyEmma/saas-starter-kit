<?php

namespace App\Contracts\Payment;

use App\Models\Transaction;
use App\Support\HttpResponse;

interface HandlesCheckout {

    public function startCheckout(Transaction $transaction): HttpResponse;

}
<?php

namespace App\Contracts\Payment;

use Illuminate\Http\Client\PendingRequest;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Support\HttpResponse;
use Illuminate\Http\Client\Response;

interface PaymentGateway {

    public function client(): mixed;

    public function verify(Transaction $transaction): HttpResponse;

    public function subscribe(Transaction $transaction): array;
    // public function onResponse(HttpResponse $httpResponse, Transaction $transaction): array;
    
    public function checkout(Transaction $transaction): array;

    function buildResponse(Response $response): HttpResponse;

}
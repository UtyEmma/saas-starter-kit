<?php

namespace App\Contracts\Payment;

use App\Models\Subscription;
use App\Models\Transaction;
use App\Support\HttpResponse;

interface HandlesSubscription {

    function startSubscription(Transaction $transaction): HttpResponse;

    function cancelSubscription(Subscription $subscription): HttpResponse;

    function getSubscriptionStatus(Subscription $subscription): HttpResponse;

}
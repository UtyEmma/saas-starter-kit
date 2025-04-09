<?php

namespace App\Contracts\Payment;

use App\Models\Plans\PlanPrice;
use App\Models\Subscription;
use App\Models\Transactions\Transaction;
use App\Support\HttpResponse;

interface HandlesSubscription {

    function startSubscription(Transaction $transaction): HttpResponse;

    function cancelSubscription(Subscription $subscription): HttpResponse;

    function getSubscriptionStatus(Subscription $subscription): HttpResponse;

    function getSubscriptionId(mixed $response): string;

    function upgradeSubscription(Subscription $subscription, PlanPrice $planPrice): HttpResponse;

}
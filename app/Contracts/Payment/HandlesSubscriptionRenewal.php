<?php

namespace App\Contracts\Payment;

use App\Models\Subscription;

interface HandlesSubscriptionRenewal {

    public function renew(Subscription $subscription): array;

}
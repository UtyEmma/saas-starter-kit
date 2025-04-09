<?php

namespace App\Concerns\Subscriptions;

use App\Models\Plans\PlanPrice;
use App\Models\Subscription;

trait HandlesProration {

    function calculateProration(Subscription $subscription, PlanPrice $planPrice) {
        $unusedDays = $subscription->days - $subscription->days_used;
        $dailyRate = $subscription->transaction->amount / $subscription->days;

        return round($unusedDays * $dailyRate, 2);
    }

}
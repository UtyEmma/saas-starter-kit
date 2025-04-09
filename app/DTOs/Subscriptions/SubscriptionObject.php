<?php

namespace App\Objects\Subscriptions;

use App\Abstracts\DataTransferObject;
use App\Contracts\Payment\PaymentGateway;

class SubscriptionObject extends DataTransferObject {

    public $expires_at;
    public $starts_at;
    public $plan;
    public $user;

    public PaymentGateway $provider;


    protected function from($subscription): mixed{
        return $subscription->load(['user', 'planPrice.timeline', 'plan'])->append(['days', 'days_used', 'is_active']);
    }


}


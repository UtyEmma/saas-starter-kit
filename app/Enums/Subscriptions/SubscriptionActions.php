<?php

namespace App\Enums\Subscriptions;

enum SubscriptionActions {
    case RENEWED;
    case RENEWAL_FAILED;
    case GRACE_PERIOD;
    case TRIAL_STARTED;
    case TRIAL_ENDED;
    case EXPIRED;
    case CANCELLED;

    public function label(){
        return match($this) {
            self::RENEWED => 'Renewed',
            self::RENEWAL_FAILED => 'Renewal Failed',
            self::GRACE_PERIOD => 'Grace Period',
            self::TRIAL_STARTED => 'Trial Started',
            self::TRIAL_ENDED => 'Trial Ended',
            self::EXPIRED => 'Expired',
            self::CANCELLED => 'Cancelled',
        };
    }

}
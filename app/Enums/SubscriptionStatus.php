<?php

namespace App\Enums;

enum SubscriptionStatus {

    case ACTIVE;
    case CANCELLED;
    case EXPIRED;

    case TRIAL;
    case PENDING;

}
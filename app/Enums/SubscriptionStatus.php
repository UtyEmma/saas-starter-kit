<?php

namespace App\Enums;

enum SubscriptionStatus {

    case ACTIVE;
    case CANCELLED;
    case EXPIRED;

    case TRIAL;
    case PENDING;
    case GRACE;

    function label(){
        return match($this) {
            self::ACTIVE => 'ACTIVE',
            self::CANCELLED => 'CANCELLED',
            self::EXPIRED => 'EXPIRED',
            self::TRIAL => 'TRIAL',
            self::PENDING => 'PENDING',
            self::GRACE => 'GRACE',
        };
    }

    function color(){
        return match($this) {
            self::ACTIVE => 'success',
            self::CANCELLED => 'warning',
            self::EXPIRED => 'danger',
            self::TRIAL => 'info',
            self::PENDING => 'warning',
            self::GRACE => 'warning',
        };
    }

    function options(){
        return [
            self::ACTIVE->value => self::ACTIVE->label(),
            self::CANCELLED->value => self::CANCELLED->label(),
            self::EXPIRED->value => self::EXPIRED->label(),
            self::TRIAL->value => self::TRIAL->label(),
            self::PENDING->value => self::PENDING->label(),
            self::GRACE->value => self::GRACE->label(),
        ];
    }

}
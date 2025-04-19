<?php

namespace App\Models;

use App\Enums\Subscriptions\SubscriptionActions;
use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Model;

class SusbcriptionHistory extends Model
{
    protected $fillable = ['status', 'action', 'meta', 'subscription_id'];

    protected $casts = [
        'status' => SubscriptionStatus::class,
        'action' => SubscriptionActions::class
    ];

    function subscription(){
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}

<?php

namespace App\Models;

use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Model;

class SusbcriptionHistory extends Model
{
    protected $fillable = ['status', 'meta', 'subscription_id'];

    protected $casts = [
        'status' => SubscriptionStatus::class
    ];

    function subscription(){
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}

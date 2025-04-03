<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Plans\PlanPrice;
use App\Models\Plans\Plan;

class Subscription extends Model {
    
    protected $fillable = ['user_id', 'plan_id', 'plan_price_id', 'expires_at', 'reference', 'auto_renews', 'meta', 'status'];

    protected $casts = [
        'status' => SubscriptionStatus::class,
        'meta' => 'array'
    ];  

    function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    function planPrice() {
        return $this->belongsTo(PlanPrice::class, 'plan_price_id');
    }

    function plan(){
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    function scopeIsActive(Builder $query){
        $query->whereStatus(SubscriptionStatus::ACTIVE)->where('expires_at', '<=', now());
    }

    function getIsActiveAttribute () {
        return $this->status == SubscriptionStatus::ACTIVE && now()->lessThanOrEqualTo($this->expires_at);
    }

}

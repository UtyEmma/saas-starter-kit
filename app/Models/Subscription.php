<?php

namespace App\Models;

use App\Enums\PaymentGateways;
use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Plans\PlanPrice;
use App\Models\Plans\Plan;
use App\Services\TransactionService;

class Subscription extends Model {
    
    protected $fillable = ['user_id', 'plan_id', 'plan_price_id', 'expires_at', 'provider', 'reference', 'auto_renews', 'meta', 'status'];

    protected $casts = [
        'status' => SubscriptionStatus::class,
        'provider' => PaymentGateways::class,
        'meta' => 'array'
    ];  

    public static function booted(){
        self::created(function($subscription) {
            (new TransactionService($subscription->user))->create($subscription, $subscription->planPrice->price);
        });
    }

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
        $query->whereStatus(SubscriptionStatus::ACTIVE)->whereAfterToday('expires_at');
    }

    function scopeHasExpired(Builder $builder) {
        $builder->whereStatus(SubscriptionStatus::ACTIVE)->whereBeforeToday('expires_at');
    }
    
    function scopeIsExpired(Builder $builder) {
        $builder->whereStatus(SubscriptionStatus::EXPIRED);
    }

    function scopeIsExpiring(Builder $builder, $days = 7) {
        $builder->isActive()->where('expires_at', '<=', now()->addDays($days));
    }

    function getIsActiveAttribute () {
        return $this->status == SubscriptionStatus::ACTIVE && now()->lessThanOrEqualTo($this->expires_at);
    }

}

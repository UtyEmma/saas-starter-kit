<?php

namespace App\Models;

use App\Concerns\Models\HasStatus;
use App\Enums\PaymentGateways;
use App\Enums\SubscriptionStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Plans\PlanPrice;
use App\Models\Plans\Plan;
use App\Models\Transactions\Transaction;
use App\Services\TransactionService;

class Subscription extends Model {
    
    protected $fillable = ['user_id', 'plan_id', 'plan_price_id', 'expires_at', 'starts_at', 'trial_ends_at', 'gateway', 'reference', 'auto_renews', 'meta', 'status'];

    protected $casts = [
        'status' => SubscriptionStatus::class,
        'gateway' => PaymentGateways::class,
        'meta' => 'array',
        'expires_at' => 'datetime',
        'starts_at' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];  

    public static function booted(){
        self::creating(function($subscription){
            $country = locale()->country();
            $subscription->gateway = $country->gateway;
        });

        self::created(function($subscription) {
            if($subscription->status == SubscriptionStatus::PENDING) {
                (new TransactionService($subscription->user))->create($subscription, $subscription->planPrice->price);
            }
        });
    }

    function transaction(){
        return $this->morphOne(Transaction::class, 'transactable');
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
        $query->whereStatus(SubscriptionStatus::ACTIVE)->orWhere('status', SubscriptionStatus::TRIAL)
                    ->whereAfterToday('expires_at');
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

    function getProviderAttribute(){
        return $this->provider?->provider();
    }

    function getDaysUsedAttribute(){
        return $this->starts_at->diffInDays(now());
    }

    function getDaysAttribute(){
        return $this->starts_at->diffInDays($this->expires_at);
    }

}

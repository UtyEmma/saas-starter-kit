<?php

namespace App\Models;

use App\Concerns\Models\HasStatus;
use App\Enums\PaymentGateways;
use App\Enums\Subscriptions\SubscriptionActions;
use App\Enums\SubscriptionStatus;
use App\Events\Subscriptions\SubscriptionStatusUpdated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Plans\PlanPrice;
use App\Models\Plans\Plan;
use App\Models\Transactions\Transaction;
use App\Services\TransactionService;

class Subscription extends Model {
    
    protected $fillable = ['user_id', 'plan_id', 'plan_price_id', 'expires_at', 'starts_at', 'trial_ends_at', 'gateway', 'reference', 'auto_renews', 'meta', 'status', 'grace_ends_at'];

    protected $casts = [
        'status' => SubscriptionStatus::class,
        'gateway' => PaymentGateways::class,
        'meta' => 'array',
        'expires_at' => 'datetime',
        'starts_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'grace_ends_at' => 'datetime',
    ];  

    public static function booted(){
        self::creating(function($subscription){
            $subscription->gateway = locale()->country()->gateway;
        });

        self::created(function($subscription) {
            if($subscription->status == SubscriptionStatus::PENDING) {
                (new TransactionService($subscription->user))->create($subscription, $subscription->planPrice->amount);
            }
        });
    }

    function scopeByReference(Builder $query, $reference){
        $query->where('reference', $reference);
    }

    public function transaction(){
        return $this->morphOne(Transaction::class, 'transactable');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function planPrice() {
        return $this->belongsTo(PlanPrice::class, 'plan_price_id');
    }

    public function price() {
        return $this->belongsTo(PlanPrice::class, 'plan_price_id');
    }

    public function plan(){
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function history(){
        return $this->hasMany(SusbcriptionHistory::class, 'subscription_id');
    }

    public function scopeIsActive(Builder $query){
        $query->whereStatus(SubscriptionStatus::ACTIVE)->orWhere('status', SubscriptionStatus::TRIAL)
                    ->whereAfterToday('expires_at');
    }

    public function scopeHasExpired(Builder $builder, $date = null) {
        $date ??= now();
        $builder->whereStatus(SubscriptionStatus::ACTIVE)->where('expires_at', '<=', $date);
    }
    
    public function scopeIsExpired(Builder $builder) {
        $builder->whereStatus(SubscriptionStatus::EXPIRED);
    }

    public function scopeIsExpiring(Builder $builder, $days = 7) {
        $builder->isActive()->where('expires_at', '<=', now()->addDays($days));
    }

    public function getIsActiveAttribute () {
        return $this->status == SubscriptionStatus::ACTIVE && now()->lessThanOrEqualTo($this->expires_at);
    }

    public function getProviderAttribute(){
        return $this->gateway?->provider();
    }

    public function getDaysUsedAttribute(){
        return $this->starts_at->diffInDays(now());
    }

    public function getDaysAttribute(){
        return $this->starts_at->diffInDays($this->expires_at);
    }

    function getNextExpiryDateAttribute(){
        return $this->expires_at->add($this->planPrice->timeline->timeline->value, (int) $this->planPrice->timeline->count);
    }

    public function saveHistory(SubscriptionActions $action, $meta = []){
        $this->history()->create([
            'status' => $this->status,
            'description' => $action,
            'meta' => $meta
        ]);
    }

    function expire(){
        $this->status = SubscriptionStatus::EXPIRED;
        $this->save();

        $this->user->plan_id = null;
        $this->user->save();

        $this->saveHistory(SubscriptionActions::EXPIRED);
        SubscriptionStatusUpdated::dispatch($this);
        return $this;
    }

    function grace(){
        $this->status = SubscriptionStatus::GRACE;
        $this->save();

        $this->saveHistory(SubscriptionActions::GRACE_PERIOD);
        SubscriptionStatusUpdated::dispatch($this);
        return $this;
    }

}

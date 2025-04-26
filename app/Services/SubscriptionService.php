<?php

namespace App\Services;

use App\Contracts\Payment\HandlesSubscriptionRenewal;
use App\Enums\PaymentStatus;
use App\Enums\Subscriptions\SubscriptionActions;
use App\Enums\SubscriptionStatus;
use App\Events\Subscriptions\SubscriptionStatusUpdated;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanPrice;
use App\Models\Plans\Timeline;
use App\Models\Subscription;
use App\Models\Transactions\Transaction;
use App\Models\User;
use App\Support\Locale;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;

class SubscriptionService {

    protected User | Authenticatable | null $user = null;

    public function __construct(
        private TransactionService $transactionService
    ) { }

    static function make(){
        $locale = new Locale;
        $transactionService = new TransactionService();
        return new static($transactionService);
    }

    function initiate(Subscription $subscription){
        $transaction = $this->transactionService->create($subscription, $subscription->planPrice->amount);
        $paymentProvider = $transaction->provider();
        return $paymentProvider->subscribe($transaction);        
    }

    function subscribe(Transaction $transaction) {
        $subscription = $transaction->transactable;

        [$status, $message, $transaction] = $this->transactionService->verify($transaction);

        if(!$status) return state(false, $message);

        if($transaction->status == PaymentStatus::SUCCESS) {
            $subscription->status = SubscriptionStatus::ACTIVE;
            $subscription->save();

            $subscription->user->plan_id = $subscription->plan_id;
            $subscription->user->save();
        }

        return state(true, '', $subscription);
    }

    public $trial = null;

    function withTrial($interval){
        $this->trial = $interval;
        return $this;
    }

    function create($user, PlanPrice $planPrice, array $data = []) {
        $plan = $planPrice->plan;
        $trial_ends_at = $this->trial ? now()->addDays((int) $this->trial) : null;
        $starts_at = $trial_ends ?? now();

        $ends_at = Date::parse($starts_at)->add($planPrice->timeline->timeline->value, (int) $planPrice->timeline->count);
        $grace_ends_at = $plan->grace_period ? Date::parse($ends_at)->addDays((int) $plan->grace_period) : null;

        return $user->subscriptions()->create([
            'plan_id' => $planPrice->plan_id,
            'plan_price_id' => $planPrice->id,
            'expires_at' => $ends_at,
            'starts_at' => $starts_at,
            'auto_renews' => true,
            'trial_ends_at' => $trial_ends_at,
            'grace_ends_at' => $grace_ends_at,
            'status' => $this->trial ? SubscriptionStatus::TRIAL : SubscriptionStatus::PENDING
        ]);
    }
    
    function sendExpirationWarning($days = 7) {
        $expiringSubscriptions = Subscription::isExpiring($days)->with('user')->get('user');
        $users = $expiringSubscriptions->pluck('user')->unique('id');

        notify("Your subscription will expire in {$days} days")
            ->line("Just a reminder — your current subscription will expire in {{$days}} days. To avoid any interruptions in service, please make sure to renew your subscription before it ends.")
            ->action('Manage Subscription', '')
            ->priority(1)
            ->sendNow($users, ['mail']);
    }

    function cancel(Subscription $subscription){
        $response = $subscription->provider->cancelSubscription($subscription);

        if(!$response->success()) return state(false, $response->message()); 
        
        

        return state(true, $response->message());
    }

    function upgrade(Subscription $subscription, PlanPrice $planPrice) {
        if($subscription->planPrice->is($planPrice)) {
            return state(false, "You are already on the {$planPrice->plan->name} plan! You may upgrade to a different plan");
        }

        if(!$plan = $planPrice->plan) {
            return state(false, "The selected plan does not exist");
        }

        $subscription->provider->upgrade($subscription, $planPrice);
    }

    function pricing(){
        $timeline = Timeline::has('prices')->get();

        $timelines = $timeline->map(function($timeline){
            $timeline->plans = $timeline->plans()->with(['features', 'prices'])->orderBy('sort')->get()->map(function ($plan) {
                $price = $plan->prices()->find($plan->price->id);
                $plan->price->amount = $price->amount;
                $plan->price->provider_id = $price->provider_id;
                return $plan;
            });

            return $timeline;
        });

        return $timelines;
    }

    function expiredSubscriptions($date = null) {
        return Subscription::hasExpired($date)->get();
    }

    public function handleExpiredSubscriptions(Subscription $subscription) {
        if($subscription->status == SubscriptionStatus::EXPIRED) {
            return state(true, 'Subscription is already expired');
        }

        if($subscription->auto_renews) {
            if($subscription->provider instanceof HandlesSubscriptionRenewal) {
                [$status, $message, $data] = $subscription->provider->renew($subscription);
    
                if($status) {
                    $subscription->saveHistory(SubscriptionActions::RENEWED, $data);
                    return state(true, 'Subscription renewed successfully');
                }
    
                $subscription->saveHistory(SubscriptionActions::RENEWAL_FAILED, $data);
            } 
        }

        if($subscription->grace_ends_at && $subscription->grace_ends_at->isFuture()) {
            $subscription->grace();
            return state(true, 'Subscription is in grace period');
        }

        $subscription->expired();

        return state(true, 'Subscription marked as expired');
    }
    
    function markSubscriptionAsExpired(Subscription $subscription, $gracePeriod = false) {
        
    }

}

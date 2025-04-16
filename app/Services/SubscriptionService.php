<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanPrice;
use App\Models\Plans\Timeline;
use App\Models\Subscription;
use App\Models\Transactions\Transaction;
use App\Models\User;
use App\Support\Locale;
use Illuminate\Contracts\Auth\Authenticatable;
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
    public $grace = null;

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
        return $subscription->provider->cancel();
    }

    function upgrade(Subscription $subscription, Plan $plan) {
        if($subscription->plan->is($plan)) {
            return state(false, "You are already on the {$plan->name} plan! You may upgrade to a different plan");
        }

        $currentPlanPrice = $subscription->planPrice;
        if(!$newPlanPrice = $plan->pricies()->whereTimelineId($currentPlanPrice->timeline_id)->first()) {
            return state(false, "You cannot upgrade to the {$plan->name} because it does not support your current subscripton timeline. Please select a different plan.");
        }

        $subscription->provider->upgrade($subscription, $plan);
    }

    function pricing(){
        $timeline = Timeline::has('prices')->with(['plans.features', 'plans.prices'])->get();
        // Manually load the plan prices to append the country based price and provider_id accessors    
        return $timeline->map(function($timeline){
            $timeline->plans = $timeline->plans->map(function ($plan) {
                $price = $plan->prices()->find($plan->price->id);
                $plan->price->amount = $price->amount;
                $plan->price->provider_id = $price->provider_id;
                return $plan;
            });
            return $timeline;
        });
    }




}

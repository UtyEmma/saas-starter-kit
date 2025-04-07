<?php

namespace App\Services;

use App\Enums\Transactions;
use App\Models\Subscription;
use App\Models\Transactions\Transaction;
use App\Models\User;
use App\Support\Locale;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Utyemma\Chargepro\Models\Plans\PlanPrice;

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

        $paymentProvider = $transaction->paymentGateway->provider();
        return $paymentProvider->subscribe($transaction);        
    }

    function subscribe(Transaction $transaction) {
        $user = $transaction->user;
        [$status, $message, $transaction] = $this->transactionService->verify($transaction);

        if(!$status) return state(false, $message);
        if(!$planPrice = PlanPrice::with('plan')->firstWhere(['id' => $transaction->payload['plan_price']])){
            return state(false, 'The selected plan does not exist');
        }

        $subscription = $this->create($user, $planPrice);

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

        $trial_ends_at = $this->trial ? now()->addDays($this->trial) : null;

        $starts_at = $trial_ends ?? now();
        $ends_at = Date::parse($starts_at)->addDays($planPrice->timeline->days());

        $grace_ends_at = $plan->grace_period ? Date::parse($ends_at)->addDays($plan->grace_period) : null;

        return $user->subscriptions()->create([
            'plan_id' => $planPrice->plan_id,
            'plan_price_id' => $planPrice->id,
            'expires_at' => $ends_at,
            'starts_at' => $starts_at,
            'auto_renews' => true,
            'trial_ends_at' => $trial_ends_at,
            'grace_ends_at' => $grace_ends_at
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

    }

    function upgrade(Subscription $subscription) {

    }




}

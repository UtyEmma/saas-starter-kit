<?php

namespace App\Http\Controllers;

// use App\Services\SubscriptionService;

use App\Models\Transaction;
use App\Services\SubscriptionService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Utyemma\Chargepro\Models\Plans\PlanPrice;

class SubscriptionController extends Controller {

    function __construct(
        private SubscriptionService $subscriptionService,
        private TransactionService $transactionService
    ){ }

    function startTrial(Request $request, PlanPrice $planPrice) {
        $plan = $planPrice->plan;

        if(!$plan->trial_period) return back()->with('error', 'Trial is not available for this plan');
        $user = Auth::user();
        
        $this->subscriptionService->withTrial($plan->trial_period)->create($user, $planPrice);
        return back()->with('success', '');
    }

    function initiate(Request $request, PlanPrice $planPrice) {
        $user = Auth::user();
        [$status, $message, $data] = $this->subscriptionService->initiate($user, $planPrice);
        if(!$status) return back()->with('error', $message);
        return back()->with('success', $data);
    }

    function verify(Request $request, Transaction $transaction) {
        [$status, $message, $data] = $this->subscriptionService->subscribe($transaction);
        if(!$status) return back()->with('error', $message);
        return back()->with('success', $message);
    }
}

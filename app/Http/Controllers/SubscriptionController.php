<?php

namespace App\Http\Controllers;

// use App\Services\SubscriptionService;

use App\Models\Plans\PlanPrice;
use App\Models\Transactions\Transaction;
use App\Services\SubscriptionService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller {

    function __construct(
        private SubscriptionService $subscriptionService,
        private TransactionService $transactionService
    ){ }

    function startTrial(Request $request, PlanPrice $planPrice) {
        try {        
            DB::beginTransaction();
            $plan = $planPrice->plan;
    
            if(!$plan->trial_period) return back()->with('error', 'Trial is not available for this plan');
            $user = authenticated();
    
            $planPrice->load(['plan', 'timeline']);
            
            $this->subscriptionService->withTrial($plan->trial_period)->create($user, $planPrice);
            DB::commit(); 
            
            return back()->with('success', '');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    function checkout(Request $request, PlanPrice $planPrice) {
        try {
            DB::beginTransaction();
            $user = authenticated();
            $subscription  = $this->subscriptionService->create($user, $planPrice);
            [$status, $message, $data] = $this->subscriptionService->initiate($subscription);
            if(!$status) return back()->with('error', $message);
            DB::commit();

            if($data['redirect']) return inertia()->location($data['url']);
            return back()->with('success', $data);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    function verify(Request $request, Transaction $transaction) {
        [$status, $message, $data] = $this->subscriptionService->subscribe($transaction);
        if(!$status) return back()->with('error', $message);
        return back()->with('success', $message);
    }
}

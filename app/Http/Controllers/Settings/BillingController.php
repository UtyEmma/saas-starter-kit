<?php

namespace App\Http\Controllers\Settings;

use App\Features\SendApiRequestFeature;
use App\Http\Controllers\Controller;
use App\Http\Resources\Billing\SubscriptionResource;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class BillingController extends Controller {

    function __construct(protected SubscriptionService $subscriptionService) {

    }
    
    function index(){
        $user = authenticated(['subscription.plan', 'subscription.price']);

        $pricing = $this->subscriptionService->pricing();
        $subscription = new SubscriptionResource($user->subscription);
        return Inertia::render('settings/billing/Index', compact('pricing', 'subscription'));
    }

}

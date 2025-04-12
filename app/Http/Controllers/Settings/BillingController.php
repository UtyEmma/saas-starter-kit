<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BillingController extends Controller {

    function __construct(protected SubscriptionService $subscriptionService) {

    }
    
    function index(){
        $pricing = $this->subscriptionService->pricing();
        return Inertia::render('settings/billing/Index', compact('pricing'));
    }

}

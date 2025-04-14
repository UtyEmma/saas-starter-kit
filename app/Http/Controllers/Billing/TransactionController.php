<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Transactions\Transaction;
use App\Services\SubscriptionService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    function __construct(
        private TransactionService $transactionService,
        private SubscriptionService $subscriptionService
    ) {

    }

    function verify(Transaction $transaction) {
        [$status, $message, $data] = match($transaction->transactable_type) {
            Subscription::class => $this->subscriptionService->subscribe($transaction),
            default => $this->transactionService->verify($transaction)
        };

        if(!$status) return to_route('dashboard')->with(['error' => $message]);
        return to_route('dashboard')->with(['success' => $message]);
    }
}

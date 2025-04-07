<?php

namespace App\PaymentGateways\Stripe;

use App\Abstracts\BasePaymentGateway;
use App\Contracts\Payment\HandlesCheckout;
use App\Contracts\Payment\HandlesSubscription;
use App\Contracts\Payment\RedirectPayment;
use App\Enums\PaymentStatus;
use App\Enums\RequestStatus;
use App\Models\Transactions\Transaction;
use App\PaymentGateways\Stripe\Concerns\ManagePayment;
use App\PaymentGateways\Stripe\Concerns\ManageSubscriptions;
use App\Support\HttpResponse;
use Stripe\StripeClient;

class StripeGateway extends BasePaymentGateway implements RedirectPayment, HandlesSubscription, HandlesCheckout {
    use ManageSubscriptions, ManagePayment;

    protected StripeClient $stripeClient;

    function client(): StripeClient {
        return new StripeClient('');
    }

    function verify(Transaction $transaction): HttpResponse {
        try {
            $session = $this->stripeClient->checkout->sessions->retrieve($transaction->provider_reference);

            $state = match ($session->payment_status) {
                'paid' => PaymentStatus::SUCCESS,
                'unpaid' => PaymentStatus::PENDING,
                default => PaymentStatus::CANCELLED
            };

            return $this->response(RequestStatus::OK, $session->toArray(), $state);
        } catch (\Throwable $th) {
            return $this->response(RequestStatus::ERROR, ['message' => $th->getMessage()]);
        }
    }

    

    
    
    

}
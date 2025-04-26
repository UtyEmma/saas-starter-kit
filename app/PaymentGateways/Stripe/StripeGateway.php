<?php

namespace App\PaymentGateways\Stripe;

use App\Abstracts\BasePaymentGateway;
use App\Contracts\Payment\HandlesCheckout;
use App\Contracts\Payment\HandlesSubscription;
use App\Contracts\Payment\HandlesWebhook;
use App\Contracts\Payment\RedirectPayment;
use App\Enums\PaymentStatus;
use App\Enums\RequestStatus;
use App\Models\Transactions\Transaction;
use App\PaymentGateways\Stripe\Concerns\ManagePayment;
use App\PaymentGateways\Stripe\Concerns\ManageSubscriptions;
use App\PaymentGateways\Stripe\Concerns\ManageWebhooks;
use App\Support\HttpResponse;
use Exception;
use Stripe;

class StripeGateway extends BasePaymentGateway implements RedirectPayment, HandlesSubscription, HandlesCheckout, HandlesWebhook {
    use ManageSubscriptions, ManagePayment, ManageWebhooks;

    function client(): Stripe\StripeClient {
        return new Stripe\StripeClient(env('STRIPE_SECRET'));
    }

    function verify(Transaction $transaction): HttpResponse {
        try {
            $session = $this->client->checkout->sessions->retrieve($transaction->provider_id);

            if(!isset($session->payment_status)) throw new Exception($session->message ?? 'Invalid Payment verification Response from payment provider');

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
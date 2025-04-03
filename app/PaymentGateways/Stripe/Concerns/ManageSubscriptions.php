<?php

namespace App\PaymentGateways\Stripe\Concerns;

use App\Enums\RequestStatus;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Support\HttpResponse;

trait ManageSubscriptions {

    function callbackUrl(array $params = []): string {
        return route('profile.edit', $params);
    }

    function startSubscription(Transaction $transaction): HttpResponse {
        try {
            $checkout = $this->client->checkout->sessions->create([
                'success_url' => $this->callbackUrl([]),
                'mode' => 'subscription',
                'client_reference_id' => $transaction->reference,
                'customer_email' => $transaction->payload['email'],
                'currency' => strtolower($transaction->currency_code)
            ]);

            return $this->response(RequestStatus::OK, $checkout, [
                'url' => $checkout->url
            ]);
        } catch (\Throwable $th) {
            return $this->response(RequestStatus::ERROR, ['error' => $th->getMessage()]);
        }
    }

    function cancelSubscription(Subscription $subscription): HttpResponse {
        return $this->response(RequestStatus::OK);
    }

    function getSubscriptionStatus(Subscription $subscription): HttpResponse {
        return $this->response(RequestStatus::OK);
    }

}
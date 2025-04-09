<?php

namespace App\PaymentGateways\Stripe\Concerns;

use App\Enums\RequestStatus;
use App\Models\Plans\PlanPrice;
use App\Models\Subscription;
use App\Models\Transactions\Transaction;
use App\Support\HttpResponse;
use Stripe\Subscription as StripeSubscription;

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

            $transaction->provider_reference = $checkout->id;
            $transaction->save();

            return $this->response(RequestStatus::OK, $checkout, [
                'url' => $checkout->url
            ]);
        } catch (\Throwable $th) {
            return $this->response(RequestStatus::ERROR, ['error' => $th->getMessage()]);
        }
    }

    function cancelSubscription(Subscription $subscription): HttpResponse {
        try {
            $response = $this->client->subscriptions->update($subscription->reference, [
                'cancel_at_period_end' => true
            ]);

            return $this->response(RequestStatus::OK, $response);
        } catch (\Throwable $th) {
            return $this->response(RequestStatus::ERROR, [
                'error' => $th->getMessage()
            ], $th->getMessage());
        }
    }

    function getSubscription(Subscription $subscription) {
        try {
            $response = StripeSubscription::retrieve($subscription->reference);
            return $this->response(RequestStatus::OK, $response);
        } catch (\Throwable $th) {
            return $this->response(RequestStatus::ERROR, [
                'error' => $th->getMessage()
            ], $th->getMessage());
        }
    }

    function getSubscriptionStatus(Subscription $subscription): HttpResponse {
        return $this->response(RequestStatus::OK);
    }

    function getSubscriptionId($response): string {
        return $response['subscription']['id'];
    }

    function upgradeSubscription(Subscription $subscription, PlanPrice $planPrice): HttpResponse {
        $stripeSubscription = $this->getSubscription($subscription);
        
        if(!$stripeSubscription->success()) return $stripeSubscription;
        
        try {
            $subscriptionInfo = $stripeSubscription->context();

            $response = StripeSubscription::update($subscription->reference, [
                'items' => [
                  [
                    'id' => $subscriptionInfo->items->data[0]->id,
                    'price' => $planPrice->provider_id,
                  ],
                ],
                'proration_date' => now()
              ]);

            return $this->response(RequestStatus::OK, $response);
        } catch (\Throwable $th) {
            return $this->response(RequestStatus::ERROR, [], $th->getMessage());
        }
    }

}
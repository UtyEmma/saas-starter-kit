<?php

namespace App\Abstracts;

use App\Contracts\Payment\HandlesCheckout;
use App\Contracts\Payment\HandlesSubscription;
use App\Contracts\Payment\InlinePayment;
use App\Contracts\Payment\PaymentGateway;
use App\Contracts\Payment\RedirectPayment;
use App\Enums\RequestStatus;
use App\Enums\Transactions;
use App\Models\Plans\PlanPrice;
use App\Models\Subscription;
use App\Models\Transactions\Transaction;
use App\Support\HttpResponse;
use Illuminate\Http\Client\Response;

abstract class BasePaymentGateway implements PaymentGateway {

    protected $client = null;
    private static $instance;

    static function instance(){
        if(!static::$instance) {
            static::$instance = new static;
        }
        
        static::$instance->checkCheckoutChannels();
        return static::$instance;
    }

    public function connect(){
        $this->client = $this->client(); 
        return $this;   
    }

    private function checkCheckoutChannels() {
        if(static::$instance->hasInline() && static::$instance->hasRedirect()) {
            throw new \Exception("Class ".static::class." must implement one of the ".RedirectPayment::class." interface or ".InlinePayment::class."  interface");
        }
        
        if(!static::$instance->hasInline() || !static::$instance->hasRedirect()) {
            throw new \Exception("Class ".static::class." must implement one of the ".RedirectPayment::class." interface or ".InlinePayment::class."  interface");
        }
    }

    private function onResponse(HttpResponse $httpResponse, callable | null $onFailed = null, callable | null $onSuccess = null, callable | null $context = null): array {
        if($httpResponse->failed()) {
            if($onFailed) $onFailed($httpResponse);
            return state(false, $httpResponse->message());
        }
        
        if($onSuccess) $onSuccess($httpResponse);
        
        return state(true, $httpResponse->message(), $context ? $context($httpResponse) : $httpResponse->context());
    }

    function response(RequestStatus $requestStatus, array|object $context = [], mixed $message = ''){
        return new HttpResponse($requestStatus, $context, $message);
    }

    function buildResponse(Response $response): HttpResponse {
        return $this->response(RequestStatus::OK);
    }

    function hasRedirect() {
        return static::$instance instanceOf RedirectPayment;
    }

    function hasInline(){
        return static::$instance instanceOf InlinePayment;
    }

    function subscribe(Transaction $transaction): array {
        if(!$this instanceOf HandlesSubscription) {
            $className = static::$instance;
            throw new \Exception("{$className} must implement the ".HandlesSubscription::class." interface");
        }

        $response = $this->startSubscription($transaction);
        return $this->onResponse(
            httpResponse: $response, 
            onFailed: fn($response) => $transaction->delete(),
            onSuccess: function($response) use($transaction){
                $transaction->response = $response->context();
                $transaction->save();
            },
            context: function($response) {
                return [
                    'context' => $response->context(),
                    'redirect' => $this->hasRedirect()
                ];
            }
        );
    }

    function charge(Transaction $transaction) {
        return match($transaction->type) {
            Transactions::SUBSCRIPTION => $this->subscribe($transaction),
            Transactions::PAYMENT => $this->checkout($transaction)
        };
    }

    function checkout(Transaction $transaction): array {
        if(!$this instanceOf HandlesCheckout) {
            $className = static::$instance;
            throw new \Exception("{$className} must implement the ".HandlesCheckout::class." interface");
        }
        
        $response = $this->startCheckout($transaction);

        return $this->onResponse(
            httpResponse: $response, 
            onFailed: function($response) use($transaction) {
                $transaction->saveHistory($response);
                $transaction->delete();
            },
            onSuccess: function($response) use($transaction){
                $transaction->saveHistory($response);
            },
            context: function($response) use($transaction) {
                return [
                    'transaction' => $transaction,
                    'context' => $response->context(),
                    'redirect' => $this->hasRedirect()
                ];
            }
        );
    }

    private function setSubscriptionId($response) {
        if(!$this instanceOf HandlesSubscription) {
            $className = static::$instance;
            throw new \Exception("{$className} must implement the ".HandlesSubscription::class." interface");
        }
        
        return $this->getSubscriptionId($response);
    }

    private function setCheckoutId($response) {
        if(!$this instanceOf HandlesCheckout) {
            $className = static::$instance;
            throw new \Exception("{$className} must implement the ".HandlesCheckout::class." interface");
        }
        
        return $this->getCheckoutId($response);
    }

    function complete(Transaction $transaction) {
        $response = $this->verify($transaction);

        $transactable = $transaction->transactable;

        $transactable->reference = match($transaction->type) {
            Transactions::SUBSCRIPTION => $this->setSubscriptionId($response->context()),
            Transactions::PAYMENT => $this->setCheckoutId($response->context())
        };

        $transactable->save();

        $transaction->status = $response->message();
        $transaction->save();

        $transaction->saveHistory($response);

        return state(true, '', $transaction);
    }

    function upgrade(Subscription $subscription, PlanPrice $planPrice) {
        if(!$this instanceOf HandlesSubscription) {
            $className = static::$instance;
            throw new \Exception("{$className} must implement the ".HandlesSubscription::class." interface");
        }

        $response = $this->upgradeSubscription($subscription, $planPrice);

        if(!$response->success()) return state(false, $response->context(), $response->message());

        return state(true, $response->context(), $response->message());
    }
}
<?php

namespace App\Abstracts;

use App\Contracts\FeatureContract;
use App\Enums\RequestStatus;
use App\Models\Features\Feature;
use App\Models\Features\FeatureUsage;
use App\Models\User;
use App\Support\HttpResponse;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

abstract class BaseFeature implements FeatureContract {

    protected const NO_FEATURE = 'no-feature';
    protected const LIMIT_EXCEEDED = 'limit-exceeded';

    protected const IS_ALLOWED = 'is-allowed';

    protected User | null $user = null;
    protected Feature | null $feature = null;

    function response(bool $requestStatus, array|object $context = [], mixed $message = ''){
        return new HttpResponse($requestStatus, $context, $message);
    }

    function messages() {
        return [
            self::NO_FEATURE => 'This feature is not available on your current plan! Please upgrade to a higher plan.'
        ];
    }

    protected function setMessages(Feature | null $feature, $data = []){
        return [];
    }

    private function getMessage($key, Feature | null $feature = null, $data = []){
        $messages = array_merge($this->messages(), $this->setMessages($feature, $data));
        return $messages[$key];
    }

    function getUser(User | null $user = null) {
        return $user->load('plan.features') ?? authenticated(['plan.features']) ?? null;
    }

    function check(User | null $user = null){
        $this->user = $this->getUser($user);
        
        $feature = $this->user->plan->features()->where('feature_class', static::class)->first();
    
        if(!$feature) {
            return $this->response(false, [
                'status' => self::NO_FEATURE
            ], $this->messages()[self::NO_FEATURE]);
        }

        $feature->load([
            'usage' => fn($query) => $query->when($this->user, function($query, $user){
                return $query->where('user_id', $user->id);
            })
        ]);
        
        [$status, $state, $data] = $this->resolve($feature, $this->user);

        return $this->response($status, [
            'status' => $state
        ], $this->getMessage($state, $feature, $data));        
    }

    function record(Feature $feature, $count = 1){
        $featureUsage = $feature->usage()->create([
            'user_id' => $this->user->id,
            'subscription_id' => $this->user->subscription->id,
            'count' => $count
        ]);

        $this->afterUsage($featureUsage);
    }

    function afterUsage(FeatureUsage $featureUsage): void {

    }

    function getResetPeriod(Feature $feature) {
        return now()->subtract($feature->interval, $feature->period);
    }

    function getInterval(Feature $feature, $prefix = '') {
        $interval = str($feature->interval)->lower();
        if($feature->period == 1) return "per {$interval}";
        return "every {$feature->period} ".$interval->plural($feature->period);
    }

}
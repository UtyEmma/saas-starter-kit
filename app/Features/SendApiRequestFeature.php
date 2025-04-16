<?php

namespace App\Features;

use App\Abstracts\BaseFeature;
use App\Enums\Features;
use App\Models\Features\Feature;
use App\Models\Features\FeatureUsage;
use App\Models\User;

class SendApiRequestFeature extends BaseFeature {

    public const KEY = 'send_api_request';

    protected function setMessages(Feature | null $feature, $data = []) {
        $features = str('request')->plural($feature->threshold);
        return [
            static::LIMIT_EXCEEDED => "You have exceeded the api request limits on your current plan. You are limited to {$feature->threshold} {$features} {$this->getInterval($feature)}"
        ];
    }

    public function resolve(Feature $feature, User | null $user): mixed {
        $usage_count = $feature->usage()->whereBetween('created_at', [now(), $this->getResetPeriod($feature)] )->count();
        
        if($feature->limit > $usage_count) return state(true);
        return state(false, static::LIMIT_EXCEEDED);
    }

}
<?php

namespace App\Contracts;

use App\Models\Features\Feature;
use App\Models\Features\FeatureUsage;
use App\Models\User;

interface FeatureContract {

    function resolve(Feature $feature, User | null $user): mixed;

    function afterUsage(FeatureUsage $featureUsage): void;
    

}
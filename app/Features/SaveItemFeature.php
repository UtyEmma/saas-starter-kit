<?php

namespace App\Features;

use App\Abstracts\BaseFeature;
use App\Enums\Features;
use App\Models\Features\Feature;

class SaveItemFeature extends BaseFeature {

    protected $feature = 'save_feature';

    function rules(){
        return [

        ];
    }

    function resolve(Feature $feature){

    }

    function recordUsage(Feature $feature){

    }   

}
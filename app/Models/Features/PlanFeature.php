<?php

namespace App\Models\Features;

use App\Models\Plans\Plan;
use Illuminate\Database\Eloquent\Model;

class PlanFeature extends Model {
    
    protected $fillable = ['plan_id', 'feature_id', 'limit', 'reset_period', 'reset_interval' ];

    function plan(){
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    function feature(){
        return $this->belongsTo(Feature::class, 'feature_id');
    }

}

<?php

namespace App\Models\Plans;

use App\Concerns\Models\HasStatus;
use Illuminate\Database\Eloquent\Model;
use App\Models\Plans\PlanCountryPrice;

class PlanPrice extends Model {
    use HasStatus;
    
    protected $fillable = ['plan_id', 'amount', 'provider_id', 'timeline_id'];

    function timeline(){
        return $this->belongsTo(Timeline::class, 'timeline_id');
    }

    function plan(){
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    function prices(){
        return $this->hasMany(PlanCountryPrice::class, 'price_id');
    }

    function getPriceAttribute(){
        return $this->prices()->isCurrent()->first();
    }


}

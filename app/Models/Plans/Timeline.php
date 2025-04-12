<?php

namespace App\Models\Plans;

use App\Concerns\Models\HasStatus;
use App\Enums\Timelines;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model {
    protected $fillable = ['name', 'timeline', 'shortcode', 'count'];

    protected $casts = [
        'timeline' => Timelines::class
    ];

    function scopeHasPrices(Builder $query){
        $query->has('planPrices');
    }

    function prices(){
        return $this->hasMany(PlanPrice::class, 'timeline_id');
    }

    function plans(){
        return $this->belongsToMany(Plan::class, 'plan_prices', 'timeline_id', 'plan_id')
                ->withPivot(['amount', 'provider_id', 'id'])
                ->as('price');
    }

}

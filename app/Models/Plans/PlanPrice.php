<?php

namespace Utyemma\Chargepro\Models\Plans;

use Illuminate\Database\Eloquent\Model;

class PlanPrice extends Model {
    
    protected $fillable = ['plan_id', 'price', 'timeline_id', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $attributes = [
        'is_active' => true
    ];


}

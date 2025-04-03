<?php

namespace App\Models\Plans;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model {
    use SoftDeletes;
    
    protected $fillable = ['name', 'description', 'shortcode', 'is_popular', 'description', 'trial_period', 'grace_period', 'is_active', 'is_default', 'is_free'];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    protected $attributes = [
        'is_active' => true
    ];

    function prices(){
        return $this->hasMany(PlanPrice::class);
    }

}

<?php

namespace App\Models\Plans;

use App\Concerns\Models\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model {
    use SoftDeletes, HasStatus;
    
    protected $fillable = ['name', 'description', 'is_popular', 'description', 'trial_period', 'grace_period', 'is_default', 'is_free'];

    protected $casts = [
        'is_popular' => 'boolean',
        'is_default' => 'boolean',
        'is_free' => 'boolean',
    ];

    function prices(){
        return $this->hasMany(PlanPrice::class);
    }

}

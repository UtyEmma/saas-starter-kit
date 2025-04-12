<?php

namespace App\Models\Features;

use App\Concerns\Models\HasStatus;
use App\Enums\Features;
use App\Models\Plans\Plan;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model {
    use HasStatus;

    protected $fillable = ['name', 'shortcode', 'description', 'reset_period', 'reset_interval', 'limit', 'unit'];

    function plans(){
        return $this->belongsToMany(Plan::class);
    }

}

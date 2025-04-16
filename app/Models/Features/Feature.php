<?php

namespace App\Models\Features;

use App\Concerns\Models\HasStatus;
use App\Enums\Features;
use App\Models\Plans\Plan;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model {
    use HasStatus;

    protected $fillable = ['name', 'shortcode', 'description', 'feature_class', 'reset_period', 'reset_interval', 'limit', 'unit'];

    function plans(){
        return $this->belongsToMany(Plan::class);
    }

    function usage(){
        return $this->hasMany(FeatureUsage::class);
    }

    function getInstanceAttribute(){
        return app($this->feature_class);
    }

    function check(User $user) {
        return $this->instance->check($user);
    }

    function getThresholdAttribute(){
        if (isset($this->feature) && $this->feature->limit !== null) {
            return $this->feature->limit;
        }

        return $this->limit;
    }

    function getPeriodAttribute(){
        if (isset($this->feature) && $this->feature->reset_period !== null) {
            return $this->feature->reset_period;
        }

        return $this->reset_period;
    }

    function getIntervalAttribute(){
        if (isset($this->feature) && $this->feature->reset_interval !== null) {
            return $this->feature->reset_interval;
        }

        return $this->reset_interval;
    }

}

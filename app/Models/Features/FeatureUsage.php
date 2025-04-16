<?php

namespace App\Models\Features;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FeatureUsage extends Model {
    

    protected $fillable = ['feature_id', 'user_id', 'subscription_id', 'meta', 'value', 'count'];

    function user(){
        return $this->belongsTo(User::class);
    }

    function feature(){
        return $this->belongsTo(Feature::class);
    }

    function subscription(){
        return $this->belongsTo(Subscription::class);
    }

}

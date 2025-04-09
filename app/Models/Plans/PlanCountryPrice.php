<?php

namespace Utyemma\Chargepro\Models\Plans;

use App\Models\Country;
use Illuminate\Database\Eloquent\Model;

class PlanCountryPrice extends Model {
    
    protected $fillable = ['country_id', 'price_id', 'price'];

    function scopeIsCurrent($query){
        $country = locale()->country();
        return $query->whereCountryId($country->id);
    }
    
    function country(){
        return $this->belongsTo(Country::class, 'country_id');
    }

}

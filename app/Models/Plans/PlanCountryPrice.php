<?php

namespace Utyemma\Chargepro\Models\Plans;

use Illuminate\Database\Eloquent\Model;

class PlanCountryPrice extends Model {
    
    protected $fillable = ['country_id', 'price_id', 'price'];

}

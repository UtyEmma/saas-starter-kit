<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Concerns\Models\HasStatus;
use App\Enums\PaymentGateways;

class PaymentGateway extends Model {
    use HasStatus;

    protected $fillable = ['name', 'shortcode', 'config', 'status'];

    function casts(){
        return [
            'shortcode' => PaymentGateways::class,
            'config' => 'encrypted:array'  
        ];
    }

}

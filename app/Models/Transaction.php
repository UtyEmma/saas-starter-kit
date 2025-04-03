<?php

namespace App\Models;

use App\Enums\PaymentGateways;
use App\Enums\PaymentStatus;
use App\Enums\Transactions;
use App\Support\Locale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model {
    use SoftDeletes;
    
    protected $fillable = ['reference', 'currency_code', 'payment_gateway', 'provider_id', 'payload', 'transactable_id', 'transactable_type', 'response', 'type', 'amount', 'status'];

    protected $casts = [
        'payment_gateway' => PaymentGateways::class,
        'status' => PaymentStatus::class,
        'type' => Transactions::class,
        'payload' => 'array'
    ];

    static function booted(){
        self::creating(function(Transaction $transaction){
            $country = Country::country();
            $transaction->country->associate($country);
            $transaction->currency->associate($country->currency);
        });
    }

    function provider() {
        return $this->payment_gateway->provider();
    } 

}

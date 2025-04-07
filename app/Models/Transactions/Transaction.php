<?php

namespace App\Models\Transactions;

use App\Enums\PaymentGateways;
use App\Enums\PaymentStatus;
use App\Enums\Transactions;
use App\Models\Country;
use App\Models\Subscription;
use App\Support\Locale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model {
    use SoftDeletes;
    
    protected $fillable = ['reference', 'currency_code', 'payment_gateway', 'provider_id', 'payload', 'transactable_id', 'transactable_type', 'type', 'response', 'amount', 'status'];

    protected $casts = [
        'payment_gateway' => PaymentGateways::class,
        'status' => PaymentStatus::class,
        'type' => Transactions::class,
        'payload' => 'array'
    ];

    static function booted(){
        self::creating(function(Transaction $transaction){
            $country = Country::current();
            $transaction->country->associate($country);
            $transaction->currency->associate($country->currency);
        });
    }

    function transactable(){
        return $this->morphTo();
    }

    function provider() {
        return $this->payment_gateway->provider();
    } 

    function history(){
        return $this->hasMany(TransactionHistory::class, 'transaction_id');
    }

    function getIsSubcriptionAttribute(){
        return $this->transactable_type == Subscription::class;
    }

    function saveHistory($data){
        $this->history->create([
            'meta' => $data,
            'status' => $this->status
        ]);
    }

}

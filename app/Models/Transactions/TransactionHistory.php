<?php

namespace App\Models\Transactions;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model {
    
    protected $fillable = ['status', 'meta', 'transaction_id'];

    protected $casts = [
        'status' => PaymentStatus::class,
        'meta' => 'array'
    ];

    function transaction(){
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

}

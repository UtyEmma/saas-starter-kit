<?php 

namespace App\Services;

use App\Enums\PaymentGateways;
use App\Enums\PaymentMethods;
use App\Enums\PaymentStatus;
use App\Enums\Transactions;
use App\Models\Subscription;
use App\Models\Transactions\Transaction;
use App\Models\User;
use App\Support\Locale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionService {

    function __construct(protected User | null $user = null) {
        $this->user = $user ?? authenticated();
    }

    function reference(){
        $reference = Str::random(16);
        if(Transaction::where('reference', $reference)->exists()) return $this->reference();
        return $reference;
    }

    function create(Model $transactable, float | int $amount){
        return $transactable->transaction()->create([
            'reference' => $this->reference(), 
            'payment_gateway' => $transactable->provider,
            'amount' => $amount,
            'user_id' => $transactable->user_id
        ]);
    }

    function charge(Transaction $transaction) {
        $provider = $transaction->paymentGateway->provider;
        return $provider->charge($transaction);
    }

    function retry(Transaction $transaction){

    }

    function verify(Transaction $transaction){
        $provider = $transaction->provider();
        [$status, $message, $transaction] =  $provider->complete($transaction);
        if(!$status) return state(false, $message);
        return state(true, $transaction->status->message(), $transaction);
    }

}

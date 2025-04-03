<?php 

namespace App\Services;

use App\Enums\PaymentGateways;
use App\Enums\PaymentMethods;
use App\Enums\PaymentStatus;
use App\Enums\Transactions;
use App\Models\Transaction;
use App\Support\Locale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionService {

    function __construct(private Locale $locale) {

    }

    function reference(){
        $reference = Str::random(16);
        if(Transaction::where('reference', $reference)->exists()) return $this->reference();
        return $reference;
    }

    function create($user, PaymentGateways $paymentGateway, Transactions $transactionType, $payload = []){
        return $user->transactions()->create([
            'reference' => $this->reference(), 
            'payment_gateway' => $paymentGateway,
            'currency_code' => $this->locale->currency()->code,
            'type' => $transactionType,
            ...$payload
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
        [$status, $message, $data] =  $provider->verify($transaction);
        if(!$status) return state(false, $message);

        $transaction->status = $message;
        $transaction->response = $data;
        $transaction->save();

        return state(true, $transaction->status->message(), $transaction);
    }

}

<?php

namespace App\Support;

use App\Enums\PaymentStatus;
use App\Enums\RequestStatus;
use Illuminate\Http\Client\Response;

class HttpResponse {


    function __construct(
        private RequestStatus $status,
        private array|object $context = [],
        private mixed $message = ''
    ) { }

    function context(){
        return $this->context;
    }

    function message(){
        return $this->context['message'] ?? $this->message;
    }

    function error(){
        return $this->context['error'] ?? $this->message;
    }

    function body(){
        return $this->message;
    }

    function success(){
        return $this->status == RequestStatus::OK;
    }
    
    function failed(){
        return in_array($this->status, [RequestStatus::ERROR]);
    }

    
}
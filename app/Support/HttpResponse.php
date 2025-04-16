<?php

namespace App\Support;

use App\Enums\RequestStatus;

class HttpResponse {

    function __construct(
        private RequestStatus | bool $status,
        private array|object $context = [],
        private mixed $message = ''
    ) { }

    function __get($name){
        if(isset($this->context[$name])) return $this->context[$name];
    }

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
        if(is_bool($this->status)) return $this->status;
        return $this->status == RequestStatus::OK;
    }
    
    function failed(){
        if(is_bool($this->status)) return !$this->status;
        return in_array($this->status, [RequestStatus::ERROR]);
    }

    
}
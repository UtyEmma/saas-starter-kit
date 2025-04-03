<?php

use App\Support\State;

if(!function_exists('state')) {
    function state(mixed $status, mixed $message = '', $data = []){
        $state = new State($status, $message, $data);
        return [$state->status, $state->message, $state->data];
    }
}

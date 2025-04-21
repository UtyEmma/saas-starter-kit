<?php

use App\Models\User;
use App\Support\Authenticated;
use App\Support\Locale;
use App\Support\State;
use App\Support\Toast;

if(!function_exists('state')) {
    function state(mixed $status, mixed $message = '', $data = []){
        $state = new State($status, $message, $data);
        return [$state->status, $state->message, $state->data];
    }
}

if(!function_exists('authenticated')){
    function authenticated($relations = [], $guard = 'web') : User | null {
        return Authenticated::user($relations, $guard);
    }
}

if(!function_exists('locale')) {
    function locale(){
        return new Locale;
    }
}


if(!function_exists('toast')) {
    function toast($message, $title = null){
        return new Toast($message, $title);
    }
}
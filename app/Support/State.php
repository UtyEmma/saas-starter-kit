<?php

namespace App\Support;

class State {

    function __construct(
        public readonly mixed $status, 
        public readonly mixed $message = '', 
        public readonly mixed $data = [])
    { }


}
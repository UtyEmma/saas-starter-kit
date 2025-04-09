<?php

namespace App\Abstracts;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

abstract class DataTransferObject extends Collection implements Arrayable {
    
    function __construct(mixed $data = []) {

    }

    abstract protected function from($args): mixed; 

    function create(...$args){
        return new static($this->from(...$args));
    }

}
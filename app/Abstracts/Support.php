<?php

namespace App\Abstracts;

abstract class Support {

    private static $instance = null;

    function __construct(...$args){ }

    public static function new(...$args){
        if(static::$instance) return static::$instance;
        static::$instance = new static(...$args);
        return static::$instance;
    } 

}
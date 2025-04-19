<?php

namespace App\Abstracts;

class Support {

    protected $args = [];

    private static $instance;

    public function __construct(...$args){
        foreach ($args as $arg) {
            if (is_array($arg)) {
                foreach ($arg as $key => $value) {
                    $this->$key = $value;
                }
            } else {
                $this->args[] = $arg;
            }
        }
    }

    public static function new(...$args){
        if(static::$instance) return static::$instance;
        static::$instance = new self(...$args);
        return static::$instance;
    } 

    public static function instance(...$args) {
        return self::new(...$args);
    }

}
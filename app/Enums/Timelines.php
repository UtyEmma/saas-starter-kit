<?php

namespace App\Enums;

use Carbon\Carbon;

enum Timelines:string {

    case DAY = 'DAY';
    case WEEK = 'WEEK';
    case MONTH = 'MONTH';
    case YEAR = 'YEAR';

    function days() {
        return match($this) {
            self::DAY => 1,
            self::WEEK => 7,
            self::MONTH => 30,
            self::YEAR => 360
        };
    }
    
    function label(){
        return match($this) {
            self::DAY => 'Daily',
            self::WEEK => 'Weekly',
            self::MONTH => 'Monthly',
            self::YEAR => 'Yearly'
        };
    }
    
    static function options(){
        return [
            self::DAY->value => self::DAY->label(),
            self::WEEK->value => self::WEEK->label(),
            self::MONTH->value => self::MONTH->label(),
            self::YEAR->value => self::YEAR->label()
        ];
    }

    function start(){
        // return Carbon::
    }

}
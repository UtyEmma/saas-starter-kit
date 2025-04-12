<?php

namespace App\Enums;

enum Features:string {

    case SEND_NOTICE = 'send_notice';

    function label(){
        return match($this) {
            self::SEND_NOTICE => "Send Notice"
        };
    }

    static function options(){
        return [
            self::SEND_NOTICE->value => self::SEND_NOTICE->label()
        ];
    }

}
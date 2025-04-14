<?php

namespace App\Enums;

enum Roles:string {

    case ADMIN = 'admin';
    case SUPER_ADMIN = 'super_admin';
    case USER = 'user';

    function isAdmin(){
        return $this == static::ADMIN || $this == static::SUPER_ADMIN;
    }

    function isUser(){
        return $this == static::USER;
    }

    function label(){
        return match ($this) {
            static::ADMIN => 'Administrator',
            static::SUPER_ADMIN => 'Super Admin',
            static::USER => 'User'
        };
    }

    static function options(){
        return collect([
            self::USER => self::USER->value,
            self::ADMIN => self::ADMIN->value,
            self::SUPER_ADMIN => self::SUPER_ADMIN->value
        ]);
    }

}

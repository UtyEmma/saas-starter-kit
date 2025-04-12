<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Authenticated {


    protected static User | null $user = null; 
    protected $instance = null;

    static function user($relations = [], $guard = 'web'): User | null {
        if(!static::$user) { 
            static::$user = User::with($relations)->firstWhere('id', Auth::guard($guard)->id());
            return static::$user;
        }

        if(static::$user->id == Auth::guard($guard)->id()) {
            static::$user->load($relations);
        }

        return static::$user;
    }

}
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Concerns\Models\HasStatus;
use App\Enums\Roles;
use App\Models\Features\Feature;
use App\Models\Plans\Plan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasStatus;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'plan_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => Roles::class
        ];
    }

    protected $appends = ['plan'];

    function subscriptions(){
        return $this->hasMany(Subscription::class, 'user_id');
    }

    function subscription(){
        return $this->hasOne(Subscription::class, 'user_id')->isActive();
    }

    function plan(){
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    function hasFeature(Feature $feature){
        return $feature->check($this);
    }
}

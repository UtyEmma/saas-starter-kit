<?php

namespace App\Models;

use App\Concerns\Models\HasStatus;
use App\Enums\PaymentGateways;
use App\Support\Locale;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Country extends Model {
    use HasStatus;
    
    protected $fillable = ['name', 'iso_code', 'iso_code_3', 'currency_code', 'intl_phone', 'gateway', 'is_default'];

    protected $primary_key = 'iso_code';
    public $incrementing = false;

    function casts(){
        return [
            'is_default' => 'boolean',
            'gateway' => PaymentGateways::class
        ];
    }

    protected $attributes = [
        'is_default' => false
    ];

    function scopeIsDefault($query){
        $query->where('is_default', true);
    }

    function currency(){
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    function gateway(){
        return $this->belongsTo(PaymentGateway::class, 'gateway', 'shortcode');
    }

    function getFlagAttribute(){
        return $this->getFirstMediaUrl('countries');
    }

    static function current() {
        if($user = Auth::user()) return $user->country;
        
        if(session('country')) {
            if($country = self::isActive()->first(['id' => session('country')])) return $country;
        }

        return self::isDefault()->first();
    }

    static function setCurrent(Country | null $country = null) {
        if($country) return session(['country_id' => $country->id]);
        [$status, $message, $data] = (new Locale)->current(request()->ip());
        
        if($status) {
            if($country = Country::whereIsoCode($data['countryCode'])->first()) {
                return session([ 'country' => $country ]);
            }
        }

        return session(['country' => self::current()]);
    }


}

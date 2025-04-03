<?php

namespace App\Support;

use App\Models\Country;
use Illuminate\Support\Facades\Http;

class Locale {

    function current(string $ip) {
        try {
            $data = Http::get("http://ip-api.com/json/{$ip}")->json();
            if(!$data || $data['status'] !== 'success') return state(false, '');            
            return state(true, '', $data);
        } catch (\Throwable $th) { 
            return state(false, $th->getMessage());
        }
    }

    function country() {
        return Country::current();
    }

    function currency() {
        return $this->country()->currency;
    }

}
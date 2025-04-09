<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsEncryptedArrayObject;
use Illuminate\Database\Eloquent\Model;
use App\Concerns\Models\HasStatus;

class PaymentGateway extends Model {
    use HasStatus;

    protected $fillable = ['name', 'shortcode', 'config', 'status'];

    protected $casts = [
        'config' => 'encrypted:array'
    ];

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model {
    
    protected $fillable = ['name', 'shortcode', 'interval', 'count', 'discount', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean'
    ];


}

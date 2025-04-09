<?php

namespace App\Models\Plans;

use App\Concerns\Models\HasStatus;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model {
    protected $fillable = ['name', 'shortcode', 'interval', 'count', 'discount'];



}

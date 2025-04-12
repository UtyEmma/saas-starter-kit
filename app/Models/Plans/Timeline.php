<?php

namespace App\Models\Plans;

use App\Concerns\Models\HasStatus;
use App\Enums\Timelines;
use Illuminate\Database\Eloquent\Model;

class Timeline extends Model {
    protected $fillable = ['name', 'timeline', 'shortcode', 'count'];

    protected $casts = [
        'timeline' => Timelines::class
    ];

}

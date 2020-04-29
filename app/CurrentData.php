<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrentData extends Model
{
    protected $fillable = [
        'infected', 'tested', 'recovered', 'deceased', 'country', 'last_updated_at_source', 'source_url'
    ];

    protected $casts = [
        'last_updated_at_source' => 'datetime'
    ];
}

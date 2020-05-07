<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CurrentData extends Model
{
    protected $fillable = [
        'confirmed', 'recovered', 'deaths', 'new_confirmed', 'new_recovered',
        'new_deaths', 'country', 'country_code', 'slug', 'last_update'
    ];

    protected $casts = [
        'last_update' => 'datetime'
    ];
}

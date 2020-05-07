<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoricData extends Model
{
    protected $fillable = [
        'confirmed', 'recovered', 'deaths', 'country'
    ];

    protected $casts = [
        'last_update' => 'datetime'
    ];
}

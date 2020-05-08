<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlobalData extends Model
{
    protected $fillable = [
        'confirmed', 'new_confirmed',
        'recovered', 'new_recovered',
        'deaths', 'new_deaths',
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name', 'iso2', 'iso3', 'lat', 'long', 'population'
    ];

    public function dayReports()
    {
        return $this->hasMany(DayReport::class);
    }
}

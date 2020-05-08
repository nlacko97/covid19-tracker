<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name', 'iso2', 'iso3', 'lat', 'long', 'population', 'slug'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function dayReports()
    {
        return $this->hasMany(DayReport::class);
    }
}

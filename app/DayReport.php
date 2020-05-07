<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DayReport extends Model
{
    protected $fillable = [
        'country_id', 'confirmed', 'deaths', 'recovered',
        'active', 'date'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}

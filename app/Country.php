<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'name', 'iso2', 'iso3', 'lat', 'long', 'population', 'slug'
    ];

    protected $append = ['firstConfirmedDate'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function dayReports()
    {
        return $this->hasMany(DayReport::class);
    }

    public function latestReport()
    {
        return $this->belongsTo(DayReport::class);
    }

    public function secondLatestReport()
    {
        return $this->belongsTo(DayReport::class);
    }

    public function scopeWithTwoLatestReports($query)
    {
        $query->addSelect(
            [
                'latest_report_id' => DayReport::select('id')
                    ->whereColumn('country_id', 'countries.id')
                    ->orderBy('date', 'desc')
                    ->take(1)
            ]
        )
            ->addSelect(
                [
                    'second_latest_report_id' => DayReport::select('id')
                        ->whereColumn('country_id', 'countries.id')
                        ->orderBy('date', 'desc')
                        ->skip(1)
                        ->take(1)
                ]
            )
            ->with(['latestReport', 'secondLatestReport']);
    }

    public function scopeWithCurrentStats(Builder $query)
    {
        $query->join('day_reports', function ($join) {
            $join->on('countries.id', '=', 'day_reports.country_id')->where('date', today()->subDay());
        });
    }

    public function scopeWithLatestReport($query)
    {
        $query->addSelect(
            [
                'latest_report_id' => DayReport::select('id')
                    ->whereColumn('country_id', 'countries.id')
                    ->orderBy('date', 'desc')
                    ->take(1)
            ]
        )
            ->with('latestReport');
    }

    public function getFirstConfirmedDateAttribute()
    {
        $report = $this->dayReports->where('confirmed', '>', 0)->first();
        return $report ? $report->date : today();
    }
}

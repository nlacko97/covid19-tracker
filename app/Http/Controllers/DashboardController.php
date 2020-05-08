<?php

namespace App\Http\Controllers;

use App\Country;
use Illuminate\Http\Request;
use App\Charts\CurrentDataChart;
use App\Charts\HistoricDataChart;
use App\DayReport;
use App\GlobalData;

class DashboardController extends Controller
{
    public function index(Country $country = null)
    {
        if ($country) {
            $historicChart = new HistoricDataChart($country);
            $currentChart = new CurrentDataChart($country);
        }
        $latest = DayReport::whereDate('date', now()->subDay());
        $prev = DayReport::whereDate('date', now()->subDays(2));
        $confirmed = $latest->sum('confirmed');
        $confirmed_prev = $prev->sum('confirmed');
        $recovered = $latest->sum('recovered');
        $recovered_prev = $prev->sum('recovered');
        $deaths = $latest->sum('deaths');
        $deaths_prev = $prev->sum('deaths');
        $globalData = new GlobalData([
            'confirmed' => $confirmed,
            'new_confirmed' => $confirmed - $confirmed_prev,
            'recovered' => $recovered,
            'new_recovered' => $recovered - $recovered_prev,
            'deaths' => $deaths,
            'new_deaths' => $deaths - $deaths_prev
        ]);
        if ($country) {
            return view('welcome', [
                'countries' => Country::all(),
                'globalData' => $globalData,
                'country' => $country,
                'currentChart' => $currentChart,
                'historicChart' => $historicChart,
            ]);
        } else {
            return view('welcome', [
                'countries' => Country::all(),
                'globalData' => $globalData,
                'country' => $country,
            ]);
        }
    }
}

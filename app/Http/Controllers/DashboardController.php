<?php

namespace App\Http\Controllers;

use App\Country;
use Illuminate\Http\Request;
use App\Charts\CurrentDataChart;
use App\Charts\HistoricDataChart;
use App\DayReport;
use App\GlobalData;
use Artesaos\SEOTools\Facades\SEOTools;

class DashboardController extends Controller
{
    public function index(Country $country = null)
    {
        SEOTools::setTitle(($country ? $country->name . ' - ' : '') . 'COVID-19 Stats Tracker');
        SEOTools::setDescription('This website has been independently developed for personal purposes to track and visualize current and historic data of the COVID-19 pandemic throughout the world starting at the beginning of the year 2020.');
        SEOTools::metatags()->addKeyword(['corona', 'covid', 'covid-19', 'coronavirus', 'corona stats', 'covid tracker', 'coronavirus stats', 'data', 'data visualization', 'disease', 'virus', 'corona virus', 'tracking', 'open-source']);
        SEOTools::opengraph()->addImage([asset('assets/seo_ss.jpg'), 'size' => 300]);
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

<?php

namespace App\Http\Controllers;

use App\Country;
use Illuminate\Http\Request;
use App\Charts\CurrentDataChart;
use App\Charts\HistoricDataChart;
use App\DayReport;
use App\GlobalData;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Country $country = null)
    {
        SEOTools::setTitle(($country ? $country->name . ' - ' : '') . 'COVID-19 Stats Tracker');
        SEOTools::setDescription('This website has been independently developed for personal purposes to track and visualize current and historic data of the COVID-19 pandemic throughout the world starting at the beginning of the year 2020.');
        SEOTools::metatags()->addKeyword(['corona', 'covid', 'covid-19', 'coronavirus', 'corona stats', 'covid tracker', 'coronavirus stats', 'data', 'data visualization', 'disease', 'virus', 'corona virus', 'tracking', 'open-source']);
        SEOTools::opengraph()->addImage([asset('assets/seo_ss.jpg'), 'size' => 300]);
        if ($country) {
            $country->load('dayReports');
            $historicChart = new HistoricDataChart($country);
            $currentChart = new CurrentDataChart($country);
        }
        $countries = Country::withLatestReport()->get();
        $globalStatsToday = DB::select(DB::raw('select sum(t1.confirmed) as total_confirmed, sum(t1.deaths) as total_deaths, sum(recovered) as total_recovered
        from day_reports t1
        where t1.date = (
            select max(t2.date)
            from day_reports t2
            where t2.country_id = t1.country_id
        )'))[0];
        $globalStatsYesterday = DB::select(DB::raw('select sum(t1.confirmed) as total_confirmed, sum(t1.deaths) as total_deaths, sum(recovered) as total_recovered
            from day_reports t1
            where t1.date = (
                select max(t2.date)
                from day_reports t2
                where t2.country_id = t1.country_id
                and t2.date < (select max(t3.date) from day_reports t3 where t3.country_id = t2.country_id)
            )'))[0];
            // dd($globalStatsToday, $globalStatsYesterday);
        $globalData = new GlobalData([
            'confirmed' => $globalStatsToday->total_confirmed,
            'new_confirmed' => $globalStatsToday->total_confirmed - $globalStatsYesterday->total_confirmed,
            'recovered' => $globalStatsToday->total_recovered,
            'new_recovered' => $globalStatsToday->total_recovered - $globalStatsYesterday->total_recovered,
            'deaths' => $globalStatsToday->total_deaths,
            'new_deaths' => $globalStatsToday->total_deaths - $globalStatsYesterday->total_deaths,
        ]);
        if ($country) {
            return view('welcome', [
                'countries' => $countries,
                'globalData' => $globalData,
                'country' => $country,
                'currentChart' => $currentChart,
                'historicChart' => $historicChart,
            ]);
        } else {
            return view('welcome', [
                'countries' => $countries,
                'globalData' => $globalData,
                'country' => $country,
            ]);
        }
    }
}

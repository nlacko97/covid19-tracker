<?php

namespace App\Http\Controllers;

use App\Charts\CurrentDataChart;
use App\Charts\HistoricDataChart;
use App\CurrentData;
use App\HistoricData;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $currentData = CurrentData::first();
        if (!$currentData) {
            $response = Http::get('https://api.apify.com/v2/key-value-stores/KUlj8EGfDGHiB0gU1/records/LATEST?disableRedirect=true');
            $data = $response->json();
            CurrentData::create([
                'infected' => $data['infected'],
                'deceased' => $data['deceased'],
                'tested' => $data['tested'],
                'recovered' => $data['recovered'],
                'last_updated_at_source' => new Carbon($data['lastUpdatedAtSource']),
                'source_url' => $data['sourceUrl'],
                'country' => $data['country'],
            ]);
        }

        $lastTwoHistoricData = HistoricData::latest()->take(2)->get();

        // $response = Http::get('https://api.apify.com/v2/datasets/n1XtXTelVG5dJhDhy/items?format=json&clean=1');
        // $data = $response->json();
        // foreach ($data as $historicData) {
        //     HistoricData::create([
        //         'infected' => $historicData['infected'],
        //         'deceased' => $historicData['deceased'],
        //         'tested' => $historicData['tested'],
        //         'recovered' => $historicData['recovered'],
        //         'last_updated_at_source' => new Carbon($historicData['lastUpdatedAtSource']),
        //         'source_url' => $historicData['sourceUrl'],
        //         'country' => $historicData['country'],
        //     ]);
        // }

        $currentDataChart = new CurrentDataChart($currentData);
        $historicDataChart = new HistoricDataChart();
    
        return view('welcome', [
            'currentData' => $currentData,
            'currentDataChart' => $currentDataChart,
            'historicDataChart' => $historicDataChart,
            'lastTwoHistoricData' => $lastTwoHistoricData
        ]);
    }
}

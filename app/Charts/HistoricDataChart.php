<?php

namespace App\Charts;

use App\Country;
use Carbon\Carbon;
use App\HistoricData;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class HistoricDataChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct(Country $country)
    {
        parent::__construct();
        $this->country = $country;
        $cases = $country->dayReports;
        $this->labels($cases->pluck('date')->map(function ($item) {
            return (new Carbon($item))->format('d M Y');
        }));
        $this->dataset('Recovered', 'line', $cases->pluck('recovered'))->options([
            'backgroundColor' => 'rgba(84, 159, 147, 0.2)',
            'borderColor' => '#549F93',
            'pointRadius' => 0.4
        ]);
        $this->dataset('Confirmed', 'line', $cases->pluck('confirmed'))->options([
            'backgroundColor' => 'rgba(37, 142, 166, 0.1)',
            'borderColor' => '#258EA6',
            'pointRadius' => 0.4
        ]);
        $this->dataset('Deaths', 'line', $cases->pluck('deaths'))->options([
            'backgroundColor' => 'rgba(232, 153, 74, 0.2)',
            'borderColor' => 'rgb(232, 153, 74)',
            'pointRadius' => 0.4
        ]);
        $activeCases = $cases->map(function ($item) {
            return $item->confirmed - $item->recovered - $item->deaths;
        });
        $this->dataset('Active cases', 'line', $activeCases)->options([
            'borderColor' => 'rgb(240, 41, 41)',
            'fill' => false,
            'pointRadius' => 0
        ]);
        // $this->displayLegend(false);
        $this->displayAxes(false);
        $this->title('Outbreak trend over time');
    }
}

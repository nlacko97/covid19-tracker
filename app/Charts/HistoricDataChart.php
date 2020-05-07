<?php

namespace App\Charts;

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
    public function __construct($historicData)
    {
        parent::__construct();
        $historicData = collect($historicData);
        $this->labels($historicData->pluck('Date')->map(function ($item) {
            return (new Carbon($item))->format('d M Y');
        }));
        $this->dataset('Recovered', 'line', $historicData->pluck('Recovered'))->options([
            'backgroundColor' => 'rgba(84, 159, 147, 0.2)',
            'borderColor' => '#549F93',
            'pointRadius' => 0.4
        ]);
        $this->dataset('Confirmed', 'line', $historicData->pluck('Confirmed'))->options([
            'backgroundColor' => 'rgba(37, 142, 166, 0.1)',
            'borderColor' => '#258EA6',
            'pointRadius' => 0.4
        ]);
        $this->dataset('Deaths', 'line', $historicData->pluck('Deaths'))->options([
            'backgroundColor' => 'rgba(232, 153, 74, 0.2)',
            'borderColor' => 'rgb(232, 153, 74)',
            'pointRadius' => 0.4
        ]);
        $activeCases = $historicData->map(function ($item) {
            return $item['Confirmed'] - $item['Recovered'] - $item['Deaths'];
        });
        $this->dataset('Active cases', 'line', $activeCases)->options([
            'borderColor' => 'rgb(240, 41, 41)',
            'fill' => false,
            'pointRadius' => 0
        ]);
        // $this->displayLegend(false);
        $this->displayAxes(false);
    }
}

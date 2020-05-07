<?php

namespace App\Charts;

use App\CurrentData;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class CurrentDataChart extends Chart
{
    protected $currentData;
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct(CurrentData $currentData)
    {
        parent::__construct();
        $this->currentData = $currentData;
        
        $this->labels(['Confirmed cases', 'Recovered', 'Deaths']);
        $dataset = [
            $this->currentData->confirmed,
            $this->currentData->recovered,
            $this->currentData->deaths,
        ];
        $this->dataset('', 'bar', $dataset)->options([
            'backgroundColor' => ['#258EA6', '#549F93', 'rgb(232, 153, 74)']
        ]);
        $this->displayAxes(false);
        $this->displayLegend(false);
    }
}

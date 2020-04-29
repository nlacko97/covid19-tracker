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
        
        $this->labels(['Infected', 'Recovered', 'Deceased']);
        $dataset = [
            $this->currentData->infected,
            // $this->currentData->tested,
            $this->currentData->recovered,
            $this->currentData->deceased,
        ];
        $this->dataset('', 'bar', $dataset)->options([

            'backgroundColor' => ['#db948a', '#8adbb4', '#8aaedb']
        ]);
        $this->displayAxes(false);
        $this->displayLegend(false);
    }
}

<?php

namespace App\Charts;

use App\HistoricData;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class HistoricDataChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $historicData = HistoricData::all();

        $this->labels($historicData->pluck('last_updated_at_source')->map(function ($item) {
            return $item->format('d M Y');
        }));
        $this->dataset('deceased', 'line', $historicData->pluck('deceased'))->options([
            'backgroundColor' => '#8aaedb'
        ]);
        $this->dataset('recovered', 'line', $historicData->pluck('recovered'))->options([
            'backgroundColor' => '#8adbb4'
        ]);
        $this->dataset('infected', 'line', $historicData->pluck('infected'))->options([
            'backgroundColor' => '#db948a'
        ]);
        // $this->displayLegend(false);
        $this->displayAxes(false);
    }
}

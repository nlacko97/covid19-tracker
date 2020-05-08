<?php

namespace App\Charts;

use App\Country;
use App\CurrentData;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class CurrentDataChart extends Chart
{
    // protected $currentData;
    protected $country;
    /**
     * Initializes the chart.
     *
     * @return void
     */
    // public function __construct(CurrentData $currentData)
    // {
    //     parent::__construct();
    //     $this->currentData = $currentData;

    //     $this->labels(['Confirmed cases', 'Recovered', 'Deaths']);
    //     $dataset = [
    //         $this->currentData->confirmed,
    //         $this->currentData->recovered,
    //         $this->currentData->deaths,
    //     ];
    //     $this->dataset('', 'bar', $dataset)->options([
    //         'backgroundColor' => ['#258EA6', '#549F93', 'rgb(232, 153, 74)']
    //     ]);
    //     $this->displayAxes(false);
    //     $this->displayLegend(false);
    // }
    public function __construct(Country $country)
    {
        parent::__construct();
        $this->country = $country;
        $reports = $this->country->dayReports
            ->sortByDesc('date')
            ->take(11)
            ->values();
        foreach ($reports as $key => $report) {
            if ($key != count($reports) - 1) {
                $report->confirmed -= $reports[$key + 1]->confirmed;
                $report->deaths -= $reports[$key + 1]->deaths;
                $report->recovered -= $reports[$key + 1]->recovered;
            }
        }
        $reports->pop();
        $reports = $reports->sortBy('date');
        $this->labels($reports->pluck('date')->map(function ($item) {
            return $item->format('d M Y');
        }));
        $this->dataset('confirmed', 'bar', $reports->pluck('confirmed'))
            ->options([
                'backgroundColor' => $reports->map(function () {
                    return '#258EA6';
                })->values(),
            ]);
        $this->dataset('deaths', 'bar', $reports->pluck('deaths'))
            ->options([
                'backgroundColor' => $reports->map(function () {
                    return 'rgb(232, 153, 74)';
                })->values(),
            ]);
        $this->dataset('recovered', 'bar', $reports->pluck('recovered'))
            ->options([
                'backgroundColor' => $reports->map(function () {
                    return '#549F93';
                })->values(),
            ]);
        // $this->dataset('yesterday', 'bar', $prev_dataset)->options(['backgroundColor' => ['#258EA6', '#549F93', 'rgb(232, 153, 74)'],]);
        $this->options([
            'tooltips' => [
                'mode' => 'index'
            ],
            'scales' => [
                'xAxes' => [
                    [
                        'stacked' => true,
                        'display' => true,
                        'id' =>  'y-axis-0',
                    ],
                ],
                'yAxes' => [
                    [
                        'stacked' => true,
                        'display' => false,
                        'id' =>  'y-axis-1',
                    ],
                ],
            ]
        ]);
        // $this->displayAxes(false);
        $this->displayLegend(false);
        $this->title('Daily cases report for the past 10 days');
    }
}

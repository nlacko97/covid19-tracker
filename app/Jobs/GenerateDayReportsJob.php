<?php

namespace App\Jobs;

use App\Country;
use App\DayReport;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateDayReportsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (config('app.env') == 'production') {
            $start_time = microtime(true);
            Log::info('Entering GenereateDayReportsJob. Started generating reports');
        }
        DayReport::truncate();
        $confirmed = $this->getKeyedCsvResponse('https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/csse_covid_19_time_series/time_series_covid19_confirmed_global.csv');
        $confirmed = $this->getAggregatedData($confirmed);
        $recovered = $this->getKeyedCsvResponse('https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/csse_covid_19_time_series/time_series_covid19_recovered_global.csv');
        $recovered = $this->getAggregatedData($recovered);
        $deaths = $this->getKeyedCsvResponse('https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/csse_covid_19_time_series/time_series_covid19_deaths_global.csv');
        $deaths = $this->getAggregatedData($deaths);

        $confirmed->each(function ($item) {
            $country_id = Country::where('name', $item['Country/Region'])->first()->id;
            $excluded_keys = ['Province/State', 'Country/Region', 'Lat', 'Long'];
            foreach ($item as $key => $value) {
                if (!in_array($key, $excluded_keys)) {
                    DayReport::updateOrCreate([
                        'country_id' => $country_id,
                        'date' => new Carbon($key)
                    ], [
                        'confirmed' => $value
                    ]);
                }
            }
        });
        $recovered->each(function ($item) {
            $country_id = Country::where('name', $item['Country/Region'])->first()->id;
            $excluded_keys = ['Province/State', 'Country/Region', 'Lat', 'Long'];
            foreach ($item as $key => $value) {
                if (!in_array($key, $excluded_keys)) {
                    DayReport::updateOrCreate([
                        'country_id' => $country_id,
                        'date' => new Carbon($key)
                    ], [
                        'recovered' => $value
                    ]);
                }
            }
        });
        $deaths->each(function ($item) {
            $country_id = Country::where('name', $item['Country/Region'])->first()->id;
            $excluded_keys = ['Province/State', 'Country/Region', 'Lat', 'Long'];
            foreach ($item as $key => $value) {
                if (!in_array($key, $excluded_keys)) {
                    DayReport::updateOrCreate([
                        'country_id' => $country_id,
                        'date' => new Carbon($key)
                    ], [
                        'deaths' => $value
                    ]);
                }
            }
        });
        if (config('app.env') == 'production') {
            $end_time = microtime(true);
            Log::info('Finished generating reports in: ' . number_format($end_time - $start_time, 2, ".", "") . ' seconds. Exiting job');
        }
    }

    private function getKeyedCsvResponse($url)
    {
        $r = Http::get($url)->body();
        $lines = explode("\n", $r);
        $array = array_map("str_getcsv", $lines);
        $indexes = $array[0];
        unset($array[0]);
        array_pop($array);
        $data = collect($array)->map(function ($item) use ($indexes) {
            foreach ($item as $key => $value) {
                $item[$indexes[$key]] = $value;
                unset($item[$key]);
            }
            return $item;
        });
        return $data;
    }

    private function getAggregatedData($data)
    {
        $data = $data->map(function ($item) {
            if ($item['Province/State'] && Country::where('name', $item['Province/State'])->first()) {
                $item['Country/Region'] = $item['Province/State'];
            }
            return $item;
        })->groupBy('Country/Region')->map(function ($item) {
            if (count($item) > 1) {
                $aggregate = [
                    "Province/State" => '',
                    'Country/Region' => $item[0]['Country/Region'],
                    'Lat' => $item[0]['Lat'],
                    'Long' => $item[0]['Long'],
                ];
                $excluded_keys = ['Province/State', 'Country/Region', 'Lat', 'Long'];
                foreach ($item as $subItem) {
                    foreach ($subItem as $key => $value) {
                        if (!in_array($key, $excluded_keys)) {
                            if (!array_key_exists($key, $aggregate)) {
                                $aggregate[$key] = $value;
                            } else {
                                $aggregate[$key] += $value;
                            }
                        }
                    }
                }
                return $aggregate;
            }
            return $item->first();
        });
        $data->forget(['MS Zaandam', 'Diamond Princess']);
        return $data;
    }
}

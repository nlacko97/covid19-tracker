<?php

namespace App\Console\Commands;

use App\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GenerateCountriesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countries:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate countries from https://github.com/CSSEGISandData/COVID-19/blob/master/csse_covid_19_data/UID_ISO_FIPS_LookUp_Table.csv';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $r = Http::get('https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/UID_ISO_FIPS_LookUp_Table.csv')->body();
        $lines = explode("\n", $r);
        $array = array_map("str_getcsv", $lines);
        $indexes = $array[0];
        unset($array[0]);
        array_pop($array);
        $countries = collect($array)->map(function ($item) use ($indexes) {
            foreach ($item as $key => $value) {
                $item[$indexes[$key]] = $value;
                unset($item[$key]);
            }
            return $item;
        })->filter(function ($item) {
            return $item['UID'] == $item['code3'];
        });
        $countries_to_insert = collect();
        $countries->each(function ($item) use ($countries_to_insert) {
            $countries_to_insert->push(new Country([
                'name' => $item['Province_State'] ? $item['Province_State'] : $item['Country_Region'],
                'iso2' => $item['iso2'],
                'iso3' => $item['iso3'],
                'lat' => $item['Lat'],
                'long' => $item['Long_'],
                'population' => $item['Population'],
            ]));
        });
        Country::insert($countries_to_insert->toArray());
    }
}

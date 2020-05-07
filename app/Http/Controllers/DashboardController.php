<?php

namespace App\Http\Controllers;

use Exception;
use App\Country;
use Carbon\Carbon;
use App\CurrentData;
use App\HistoricData;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Charts\CurrentDataChart;
use App\Charts\HistoricDataChart;
use App\DayReport;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    private function getKeyedCsvResponse()
    {
        $r = Http::get('https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/csse_covid_19_time_series/time_series_covid19_confirmed_global.csv')->body();
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
    public function index()
    {
        return view('welcome', [
            'countries' => Country::with('dayReports')->get()
        ]);
    }
    public function index3()
    {
        $countries = Country::all();
        $data = $this->getKeyedCsvResponse();
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
        dd($data->count());
        $data->each(function ($item) {
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
        return 'success';
    }
    public function index2(Request $request)
    {
        $r = Cache::get('global_confirmed');
        if (!$r) {
            $r = Http::get('https://api.github.com/repos/CSSEGISandData/COVID-19/contents/csse_covid_19_data/csse_covid_19_time_series/time_series_covid19_confirmed_global.csv');
            Cache::put('global_confirmed', $r->json());
        }
        $lines = explode("\n", base64_decode($r['content']));
        $array = array_map("str_getcsv", $lines);
        $indexes = $array[0];
        unset($array[0]);
        $data = collect($array)->map(function ($item) use ($indexes) {
            foreach ($item as $key => $value) {
                $item[$indexes[$key]] = $value;
                unset($item[$key]);
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
        $data = $data->map(function ($item) {
            try {
                if (array_key_exists('Country/Region', $item)) {

                    switch ($item['Country/Region']) {
                        case 'Czechia':
                            $item['Country/Region'] = 'Czech Republic';
                            break;
                        case 'US':
                            $item['Country/Region'] = 'United States of America';
                            break;
                        case 'Burma':
                            $item['Country/Region'] = 'Myanmar';
                            break;
                        case "Iran":
                            $item['Country/Region'] = 'Iran (Islamic Republic of)';
                            break;
                        case "Congo (Brazzaville)":
                            $item['Country/Region'] = 'Congo-Brazzaville';
                            break;
                        case "Congo (Kinshasa)":
                            $item['Country/Region'] = 'Congo-Kinshasa';
                            break;
                        case "West Bank and Gaza":
                            $item['Country/Region'] = 'Palestine';
                            break;
                        case "Taiwan*":
                            $item['Country/Region'] = 'Taiwan';
                            break;
                        case "North Macedonia":
                            $item['Country/Region'] = 'Macedonia';
                            break;
                        case "Korea, South":
                            $item['Country/Region'] = 'Korea (Democratic People\'s Republic of)';
                            break;
                    }
                }
            } catch (Exception $e) {
                dd($item, $e);
            }

            return $item;
        });
        $data->pop();
        $data->forget(['MS Zaandam', 'Diamond Princess']);


        // $countries = Cache::get('countries_ok');
        // $failed_countries = Cache::get('failed_countries');
        // $failed_countries = collect($failed_countries)->map(function ($item) {
        //     if (array_key_exists('status', $item)) {
        //         return $item;
        //     } else {
        //         $good = [];
        //         try {
        //             $original_country = $item['original_country'];
        //             array_pop($item);
        //             foreach ($item as $cr) {
        //                 if ($cr['name'] == $original_country) {
        //                     $good[0] = $cr;
        //                     break;
        //                 }
        //             }
        //             $good['original_country'] = $original_country;
        //         } catch (Exception $e) {
        //             dd($item, $e);
        //         }
        //         return $good;
        //     }
        // });
        // foreach ($failed_countries as $key => $c) {
        //     if (count($c) == 2) {
        //         $countries->push($c);
        //         $failed_countries->forget($key);
        //     }
        // }
        // $countries = collect([]);
        // $failed_countries = collect([]);
        // foreach ($data->pluck('Country/Region') as  $country) {
        //     $countryResponse = Http::get('https://restcountries.eu/rest/v2/name/' . $country)->json();
        //     $countryResponse['original_country'] = $country;
        //     if (count($countryResponse) > 2) {
        //         $failed_countries->push($countryResponse);
        //     } else {
        //         $countries->push($countryResponse);
        //     }
        // }
        // $countries = $countries->sortByDesc(function ($item) {
        //     return $item[0]['population'];
        // });
        // Cache::put('countries_ok', $countries);
        // Cache::put('failed_countries', $failed_countries);
        // dd($countries, $failed_countries);
        // dd(collect(Cache::get('summaryData')['Countries'])->count());
        // $countries = $data->keys()->map(function ($item) {return Str::slug($item);});
        // dd($data);


        // $currentData = CurrentData::first();
        // if (!$currentData) {
        //     $response = Http::get('https://api.apify.com/v2/key-value-stores/KUlj8EGfDGHiB0gU1/records/LATEST?disableRedirect=true');
        //     $data = $response->json();
        //     CurrentData::create([
        //         'infected' => $data['infected'],
        //         'deceased' => $data['deceased'],
        //         'tested' => $data['tested'],
        //         'recovered' => $data['recovered'],
        //         'last_updated_at_source' => new Carbon($data['lastUpdatedAtSource']),
        //         'source_url' => $data['sourceUrl'],
        //         'country' => $data['country'],
        //     ]);
        // }

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
        // // $lastTwoHistoricData = HistoricData::latest()->take(2)->get();
        // $sumCount = count(Http::get('https://api.covid19api.com/summary')->json()['Countries']);
        // $cCount = count(Http::get('https://api.covid19api.com/countries')->json());
        // dd($sumCount, $cCount);
        // $summaryData = Cache::get('summaryData');
        // if (!$summaryData) {
        //     $summaryData = Http::get('https://api.covid19api.com/summary')->json();
        //     Cache::put('summaryData', $summaryData);
        // }
        // $globalData = $summaryData['Global'];
        // $countriesData = $summaryData['Countries'];
        // $countriesData = collect($countriesData)->sortByDesc('TotalConfirmed')->toArray();
        // $currentCountry = $request->country ?? collect($countriesData)->first()['Slug'];

        // $currentCountryData = collect($countriesData)->where('Slug', $currentCountry)->first();

        // $currentData = new CurrentData([
        //     'confirmed' => $currentCountryData['TotalConfirmed'],
        //     'deaths' => $currentCountryData['TotalDeaths'],
        //     'recovered' => $currentCountryData['TotalRecovered'],
        //     'new_confirmed' => $currentCountryData['NewConfirmed'],
        //     'new_deaths' => $currentCountryData['NewDeaths'],
        //     'new_recovered' => $currentCountryData['NewRecovered'],
        //     'last_update' => new Carbon($currentCountryData['Date']),
        //     'country' => $currentCountryData['Country'],
        //     'country_code' => $currentCountryData['CountryCode'],
        //     'slug' => $currentCountryData['Slug'],
        // ]);
        // $currentDataChart = new CurrentDataChart($currentData);
        // Cache::forget($currentCountry);
        // $historicData = Cache::get($currentCountry);
        // if (!$historicData) {
        //     $response = Http::get('https://api.covid19api.com/total/dayone/country/' . $currentCountry)->json();
        //     $historicData = $response;
        //     Cache::put($currentCountry, $historicData);
        // }
        // $historicDataChart = new HistoricDataChart($historicData);

        // $resp = Http::get('https://api.covid19api.com/country/romania');
        // dd($resp->json());
        // $countries = collect(Http::get('https://api.covid19api.com/countries')->json());
        return view('welcome', [
            // 'currentData' => $currentData,
            // 'currentDataChart' => $currentDataChart,
            // 'historicDataChart' => $historicDataChart,
            // 'countriesData' => $countriesData,
            // 'globalData' => $globalData
            'data' => $data,
            'countries' => $countries
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Country;
use App\DayReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestingController extends Controller
{
    public function select1()
    {
        ini_set('max_execution_time', 1800);
        /**
         * ORM
         */
        // selects
        // $output = Country::with('dayReports')->find(1);
        // $output = Country::with('dayReports')
        //     ->whereIn('id', range(1, 25))
        //     ->get();
        // $output = Country::with('dayReports')
        //     ->whereIn('id', range(1, 100))
        //     ->get();
        // $output = Country::with(['dayReports' => function ($q) {
        //     $q->where('deaths', '>', 650);
        // }])
        //     ->whereHas('dayReports', function ($q) {
        //         $q->where('deaths', '>', 650);
        //     })
        //     ->get();

        // search
        // $output = Country::with('dayReports')->where('name', 'like', '%rom%')->get();
        $output = Country::where('name', 'like', '%a%')
            ->with(['dayReports' => function ($query) {
                return $query->whereBetween('date', [date('2010-04-01'), date('2010-08-30')])->select('id', 'country_id', 'date');
            }])
            ->get();

        /**
         * QUERY BUILDER
         */

        // selects
        // $output = Country::query()
        //     ->where('countries.id', 1)
        //     ->join('day_reports', 'countries.id', '=', 'day_reports.country_id')
        //     ->get();
        // $output = Country::query()
        //     ->leftJoin('day_reports', 'countries.id', '=', 'day_reports.country_id')
        //     ->whereIn('country_id', range(1, 25))
        //     ->get();
        // $output = Country::query()
        //     ->rightJoin('day_reports', 'countries.id', '=', 'day_reports.country_id')
        //     ->whereIn('country_id', range(1, 100))
        //     ->get();
        // $output = Country::query()
        //     ->leftJoin('day_reports', 'countries.id', '=', 'day_reports.country_id')
        //     ->get();

        // search
        // $output = Country::query()
        //     ->leftJoin('day_reports', 'countries.id', '=', 'day_reports.country_id')
        //     ->where('name', 'like', '%rom%')
        //     ->get();
        // $output = Country::query()
        //     ->select(['name', 'slug', 'iso2', 'day_reports.date', 'day_reports.confirmed', 'day_reports.recovered', 'day_reports.deaths'])
        //     ->leftJoin('day_reports', 'countries.id', '=', 'day_reports.country_id')
        //     ->where('name', 'like', '%a%')
        //     ->whereBetween('date', [date('2010-04-01'), date('2010-08-30')])
        //     ->get();

        /**
         * RAW QUERY
         */
        // $output = DB::select(DB::raw('SELECT * FROM countries 
        //     LEFT JOIN day_reports ON countries.id = day_reports.country_id
        //     WHERE countries.id = 1'));
        // $output = DB::select(DB::raw('SELECT * FROM countries 
        //     LEFT JOIN day_reports ON countries.id = day_reports.country_id
        //     WHERE countries.id >= 1 AND countries.id <= 25'));
        // $output = DB::select('SELECT * FROM countries 
        //     RIGHT JOIN day_reports ON countries.id = day_reports.country_id
        //     WHERE countries.id >= 1 AND countries.id <= 100');
        // $output = DB::select('SELECT * FROM countries 
        //     RIGHT JOIN day_reports ON countries.id = day_reports.country_id 
        //     WHERE day_reports.deaths > 650');
        // dd(count($output));

        // search
        // $output = DB::select('SELECT countries.name as name, day_reports.date as date, day_reports.deaths as deaths, day_reports.recovered as recovered, day_reports.confirmed as confirmed 
        //     FROM countries
        //     LEFT JOIN day_reports ON countries.id = day_reports.country_id
        //     WHERE countries.name LIKE \'%rom%\'');
        // $output = DB::select('SELECT countries.name as name, day_reports.date as date, day_reports.deaths as deaths, day_reports.recovered as recovered, day_reports.confirmed as confirmed 
        //     FROM countries
        //     RIGHT JOIN day_reports ON countries.id = day_reports.country_id
        //     WHERE countries.name LIKE \'%a%\' AND
        //     day_reports.date BETWEEN \'2010-04-01\' AND \'2010-08-30\'');
        // dd($output);

        // $output = $output->count(); // ? 'success' : 'fail';
        return view('testing', [
            'output' => 'success'
        ]);
    }

    public function insert1()
    {
        /**
         * ELOQUENT
         */

        $country_id = Country::all()->random()->id;
        $iterations = 5000;
        $batches = 1;

        // for ($i = 0; $i < $iterations / $batches; $i++) {
        //     $to_insert = [];
        //     for ($j=0; $j < $batches; $j++) { 
        //         $to_insert[] = [
        //             'confirmed' => rand(1, 10000),
        //             'recovered' => rand(1, 10000),
        //             'deaths' => rand(1, 10000),
        //             'date' => now(),
        //             'country_id' => $country_id
        //         ];
        //     }
        //     DayReport::insert($to_insert);
        // }

        /**
         * Query Builder
         */
        // for ($i = 0; $i < $iterations / $batches; $i++) {
        //     $to_insert = [];
        //     for ($j=0; $j < $batches; $j++) { 
        //         $to_insert[] = [
        //             'confirmed' => rand(1, 10000),
        //             'recovered' => rand(1, 10000),
        //             'deaths' => rand(1, 10000),
        //             'date' => now(),
        //             'country_id' => $country_id
        //         ];
        //     }
        //     DB::table('day_reports')->insert($to_insert);
        // }
        /**
         * Raw SQL
         */
        for ($i = 0; $i < $iterations / $batches; $i++) {
            $query = 'INSERT INTO day_reports (confirmed, recovered, deaths, date, country_id) values ';
            for ($j=0; $j < $batches; $j++) { 
                if ($j) {
                    $query .= ', ';
                }
                $query .= '(' . rand(1, 10000) . ', ' . rand(1, 10000) . ', ' 
                    . rand(1, 10000) . ', "2010-02-02", ' . $country_id . ')';
            }
            DB::insert(
                $query
            );
        }
        return view('testing', [
            'output' => 'success'
        ]);
    }
}

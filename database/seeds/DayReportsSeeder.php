<?php

use App\Country;
use App\DayReport;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DayReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $countries = Country::all();
        foreach ($countries as $country) {   
            $startNumber = rand(1, 200);
            for ($j=0; $j < 10; $j++) { 
                $dayReports = [];
                for ($i=0; $i < 1000; $i++) { 
                    $dayReports[] = [
                        'date' => (new Carbon('2010-02-02'))->addDays($i),
                        'deaths' => $i > 400 ? $startNumber + $i / 2 + rand(1, 15) : 0,
                        'recovered' => $i > 200 ? $startNumber + $i + rand(1, 15) : 0,
                        'confirmed' => $i + $i / 2,
                        'country_id' => $country->id
                    ];
                }
                DayReport::insert($dayReports);
            }
        }
        
    }
}

<?php

namespace Database\Seeders;

use App\Models\Finance\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected  $companies = [
        [
            'name' => 'casamaintenance',
            'website' => 'https://casamaintenance.ma',
            'city' => 'casablanca',
            'addresse' => '178 si4 zone industrie ouled saleh, Bouskoura',
            'telephone' => '05223-34943',
            'email' => 'contact@casamaintenance.ma',
            'rc' => '431120111',
            'ice' => '0026002211545',
            'cnss' => '431120111',
        ],
        [
            'name' => 'industronics unlimited',
            'website' => 'https://industronicsunlimited.ma',
            'city' => 'casablanca',
            'addresse' => '178 si4 zone industrie ouled saleh, Bouskoura',
            'telephone' => '05223-34940',
            'email' => 'industronicsunlimited@gmail.com',
            'rc' => '4311201',
            'ice' => '000191639000018',
            'cnss' => '41111',
        ]
    ];

    public function run()
    {
        if (Company::count() <= 0) {
            
            Company::createMany($this->companies);
        }
    }
}

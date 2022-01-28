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
            'name' => 'casamaintenance S.R.A.L',
            'website' => 'https://casamaintenance.ma',
            'city' => 'casablanca',
            'addresse' => '178 si4 zone industrie ouled saleh, Bouskoura',
            'telephone' => '05223-34943',
            'email' => 'contact@casamaintenance.ma',
            'rc' => '466653',
            'ice' => '002544355000046',
            'cnss' => '2077521',
            'patente' => '72020004',
            'if' => '45888553',

            'prefix_invoice' => 'FCT-CASA-',
            'invoice_start_number' => 188
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
            'patente' => '72020',
            'if' => '45888',

            'prefix_invoice' => 'FCT-INDU-',
            'invoice_start_number' => 523
        ]
    ];

    public function run()
    {
        if (Company::count() <= 0) {

            foreach ($this->companies as $company) {

                Company::create($company);
            }
        }
    }
}

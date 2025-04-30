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
    protected array $companies = [
        [
            'name' => 'A- S.R.A.L',
            'website' => 'https://google.ma',
            'city' => 'casablanca',
            'addresse' => 'Bouskoura',
            'telephone' => '000000000000',
            'email' => 'contact@google.ma',
            'rc' => '00000000',
            'ice' => '000000000000',
            'cnss' => '0000000',
            'patente' => '0000000000',
            'if' => '0000000000',

            'prefix_invoice' => 'FCT-',
            'invoice_start_number' => 188,

            'prefix_invoice_avoir' => 'AVOIR-CASA-',
            'invoice_avoir_start_number' => 33,

            'prefix_estimate' => 'DEVIS-CASA-',
            'estimate_start_number' => 112,

            'prefix_bcommand' => 'BON-',
            'bcommand_start_number' => 19,

            'prefix_blivraison' => 'BL-',
            'blivraison_start_number' => 10,

        ],
        [
            'name' => 'B- SARL',
            'website' => 'https://google.com',
            'city' => 'casablanca',
            'addresse' => 'Bouskoura',
            'telephone' => '0000000000',
            'email' => 'google@gmail.com',
            'rc' => '0000',
            'ice' => '000',
            'cnss' => '000900000000',
            'patente' => '10101010000',
            'if' => '01200000000000',

            'prefix_invoice' => 'FCT-INDU-',
            'invoice_start_number' => 523,

            'prefix_invoice_avoir' => 'AVOIR-INDU-',
            'invoice_avoir_start_number' => 13,

            'prefix_estimate' => 'DEVIS-INDU-',
            'estimate_start_number' => 12,

            'prefix_bcommand' => 'BON-',
            'bcommand_start_number' => 190,

            'prefix_blivraison' => 'BL-',
            'blivraison_start_number' => 190,
        ],
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

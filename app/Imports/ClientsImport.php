<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;

class ClientsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Client([
            'code' => $row[0],
            'entreprise' => $row[1],
            'contact' => $row[2],
            'telephone' => $row[3],
            'email' => $row[4],
            'addresse' => $row[5],
            'rc' => $row[6],
            'ice' => $row[7],
        ]);
    }
}

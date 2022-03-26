<?php

declare(strict_types=1);

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\ClientsExport;
use Maatwebsite\Excel\Facades\Excel;

class ClientExportController extends Controller
{


    public function export()
    {
        return Excel::download(new ClientsExport, 'clients.xlsx');
    }
}

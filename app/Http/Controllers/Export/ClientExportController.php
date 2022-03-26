<?php

declare(strict_types=1);

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\ClientsExport;
use App\Rules\StoreToDisk;
use Maatwebsite\Excel\Facades\Excel;

class ClientExportController extends Controller
{


    public function export()
    {
        return Excel::download(new ClientsExport, 'clients.xlsx');
    }

    public function storeToDisk(Request $request)
    {

        $disk = (object)validator($request->route()->parameters(), [

            'disk' => ['required', new StoreToDisk()]

        ])->validate();

        $fileName = now()->format('d-m-Y') . '-clients-list' . '.xlsx';

        $store = Excel::store(new ClientsExport, $fileName, $disk->disk);

        if ($store) {
            
            return redirect()->back()->with('success', "Le backup a été crée avec success est souvgarder sur google DRIVE");
        }
        return redirect()->back()->with('error', "error ...");
    }
}

<?php

namespace App\Http\Controllers\Site;

use App\Domain\Support\Helpers\DomainHelpers;
use App\Http\Controllers\Controller;
use App\Models\Authentification\Admin;
use App\Repositories\Admin\AdminInterface;
use Elmarzougui\Payment\Payment;
use Illuminate\Http\Request;

class SiteController extends Controller
{


    public function index()
    {

        // return Payment::_payment()->getPayment();

        //  return $payment->getPayment();

        //return getPrice(20);
        $admins = app(AdminInterface::class)->getAdmins();

       
            //$guardName = (new \ReflectionClass(Admin::class))->getDefaultProperties()['guard_name'] ?? null;
           // $guardName = new \ReflectionClass(Admin::class);
           //  dd($guardName->getInterfaceNames());

        return view('theme.pages.Home.index', compact('admins'));
    }

    public function admins(): array
    {

        $admins = Admin::all()->groupByPosition();

        return $admins;
    }

    public function dashboard(): string
    {
        return "hello admins";
    }

    public function settings(): string
    {
        return "hello settings";
    }

    public function profile(): string
    {
        return "hello profile";
    }


    public function helpers()
    {
        $helpers = DomainHelpers::new();

        return $helpers->getHelpers();
    }

    public function welcom()
    {
        return "hellooo welcvom";
    }
}

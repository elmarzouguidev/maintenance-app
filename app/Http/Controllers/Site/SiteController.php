<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Authentification\Admin;
use Elmarzougui\Payment\Payment;
use Illuminate\Http\Request;

class SiteController extends Controller
{


  public function index()
  {

    // return Payment::_payment()->getPayment();

    //  return $payment->getPayment();

    //return getPrice(20);

    return view('theme.pages.Home.index');
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
}

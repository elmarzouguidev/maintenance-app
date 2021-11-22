<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
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
}

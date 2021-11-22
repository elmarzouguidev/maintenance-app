<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Elmarzougui\Payment\Payment;
use Illuminate\Http\Request;

class SiteController extends Controller
{


    public function index()
    {

        $payment = new Payment();

      //  return $payment->getPayment();

        return getPrice(15);
    }
}

<?php

namespace App\Http\Controllers\Developper;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DevController extends Controller
{


    public function truncateModels()
    {
      return  Model::truncate();
    }
}

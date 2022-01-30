<?php

namespace App\Http\Controllers\Developper;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DevController extends Controller
{

  protected $tables = ['failed_jobs', 'jobs', 'job_batches'];

  public function truncateModels()
  {
    return  Model::truncate();
  }

  public function clearJobs()
  {
    foreach ($this->tables as $name) {
      //if you don't want to truncate migrations
      //if ($name == 'migrations') {
      //continue;
      //}
      DB::table($name)->truncate();
    }
  }
}

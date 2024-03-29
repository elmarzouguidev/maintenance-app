<?php

namespace App\Http\Controllers\Developper;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DevController extends Controller
{
    protected array $tables = [];

    public function composerDump()
    {
        \shell_exec('composer dump-autoload');
    }

    public function composerUpdate()
    {
        \shell_exec('composer update');
    }

    public function clearTables()
    {
        foreach ($this->tables as $name) {
            //if you don't want to truncate migrations
            //if ($name == 'migrations') {
            //continue;
            //}
            //  DB::table($name)->truncate();
        }
    }

    public function storageLink()
    {
        Artisan::call('storage:link');
    }

    public function storageUnLink()
    {
        \shell_exec('cd public');
        \shell_exec('rm storage');
    }

    public function cleareAll()
    {
        Artisan::call('optimize:clear');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
    }

    public function cacheAll()
    {
        Artisan::call('optimize');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
    }

    public function migrateAll()
    {
        Artisan::call('migrate', [
            '--force' => true,
        ]);
    }

    public function migrateSeed()
    {
        Artisan::call('db:seed', [
            '--force' => true,
        ]);
    }

    public function migrateSeedWithClass(string $class)
    {
        //dd($class);
        Artisan::call('db:seed', [
            '--force' => true,
            '--class' => $class,
        ]);
    }

    public function appDown()
    {
        Artisan::call('down');
    }

    public function appUp()
    {
        Artisan::call('up');
    }

    public function installer()
    {
        Artisan::call('app:install');
    }

    public function livewireConfig()
    {
        //Artisan::call('livewire:publish', ['--config' => true]);
        Artisan::call('vendor:publish', ['--force' => true, '--tag' => 'livewire:config']);
    }

    public function livewireAssets()
    {
        //Artisan::call('livewire:publish', ['--assets' => true]);
        Artisan::call('vendor:publish', ['--force' => true, '--tag' => 'livewire:assets']);
    }

    public function livewireDiscover()
    {
        //Artisan::call('livewire:publish', ['--assets' => true]);
        Artisan::call('livewire:discover');
    }
}

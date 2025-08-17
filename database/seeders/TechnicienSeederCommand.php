<?php

namespace Database\Seeders;

use Illuminate\Console\Command;

class TechnicienSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:techniciens {--count=15 : Number of technicians to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed technicians with realistic data for development';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting TechnicienSeeder...');
        
        $seeder = new TechnicienSeeder();
        $seeder->setCommand($this);
        $seeder->run();
        
        $this->info('TechnicienSeeder completed successfully!');
        
        return 0;
    }
}

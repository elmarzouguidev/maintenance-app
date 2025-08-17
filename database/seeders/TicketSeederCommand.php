<?php

namespace Database\Seeders;

use Illuminate\Console\Command;

class TicketSeederCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seed:tickets {--count=25 : Number of tickets to create}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed tickets with realistic data for development';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Starting TicketSeeder...');
        
        $seeder = new TicketSeeder();
        $seeder->setCommand($this);
        $seeder->run();
        
        $this->info('TicketSeeder completed successfully!');
        
        return 0;
    }
}

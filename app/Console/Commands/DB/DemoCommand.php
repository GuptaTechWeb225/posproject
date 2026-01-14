<?php

namespace App\Console\Commands\DB;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DemoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will populate demo data to database for testing purpose';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->warn('Working on it sir. And all that...');

        Artisan::call('optimize:clear');

        Artisan::call('migrate:fresh --force');

        //For demo seeder
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\DemoSeeder',
            '--force' => true
        ]);

        Artisan::call('route:cache');

        $this->info('Status: Good!! Thank You :)');
    }
}

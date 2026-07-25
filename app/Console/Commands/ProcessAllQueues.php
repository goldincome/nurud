<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ProcessAllQueues extends Command
{
    // The signature you will use in your scheduler
    protected $signature = 'app:process-all-queues';

    protected $description = 'Starts the queue worker, processes ALL queues, and stops when empty.';

    public function handle()
    {
        $this->info('Starting global queue worker...');

        // Removing '--queue' tells Laravel to look at all queues
        Artisan::call('queue:work', [
            '--sleep' => 3,
            '--tries' => 3,
            '--stop-when-empty' => true,
        ]);

        $this->info('All queues are empty. Worker stopped.');
        return Command::SUCCESS;
    }
}
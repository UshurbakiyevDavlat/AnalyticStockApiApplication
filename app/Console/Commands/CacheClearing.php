<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CacheClearing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cache-clearing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache clearing for the application';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->info('Cache clearing for the application...');

        $this->call('optimize:clear');

        $this->info('Cache clearing completed.');
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class IndexSearchRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index search records for meilisearch';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->postIndexing();
    }

    /**
     *  Index search post-records for Meilisearch.
     *
     * @return void
     */
    private function postIndexing(): void
    {
        $this->info('Indexing search records for Meilisearch...');

        $this->call('scout:import', ['model' => 'App\Models\Post']);

        $this->info('Indexing completed.');
    }
}

<?php

namespace App\Console\Commands;

use App\Services\TnService;
use Illuminate\Console\Command;

class TnTickersIsinsGet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tn-get:tickers-isins';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get tickers and isins from the TN API';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        /** @var TnService $tnService */
        $tnService = app(TnService::class);

        $this->info('Get and prepare tickers and isins from the TN API...');
        $data = $tnService->getDictionaryData();
        $this->info('Completed to get tickers and isins from the TN API.');

        $this->info('Synchronizing data.');
        $tnService->setDataToDb($data);
        $this->info('Completed to synchronized.');
    }
}

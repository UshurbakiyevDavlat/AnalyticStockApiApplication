<?php

namespace App\Console\Commands;

use App\Services\TnService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use JetBrains\PhpStorm\NoReturn;

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
    #[NoReturn] public function handle(): void
    {
        /** @var TnService $tnService */
        $tnService = app(TnService::class);

        $this->info('Get actual date for the screening');
        $actual_date = $tnService->getActualDate();
        $this->info('Actual date is - ' . $actual_date);

        $last_date = Cache::get('last_tn_securities_date');
        $this->info('Last date is - ' . $last_date);

        if ($last_date !== $actual_date) {
            $this->info('Get and prepare tickers and isins from the TN API...');
            $tnService->handleDictionaryData($actual_date);
            $this->info('Completed to get tickers and isins from the TN API.');
        }
    }
}

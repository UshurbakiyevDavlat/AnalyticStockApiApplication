<?php

use App\Services\TnService;

test('cache clearing command test', function () {
    $this->artisan('app:cache-clearing')
        ->expectsOutput('Cache clearing completed.')
        ->assertExitCode(0);
});

test('index search records', function () {
    $this->artisan('meilisearch:index')
        ->expectsOutput('Indexing completed.')
        ->assertExitCode(0);
});

test('get tickers and isins from the TN API', function () {
    $this->artisan('tn-get:tickers-isins')
        ->expectsOutput('Get actual date for the screening')
        ->expectsOutput('Get and prepare tickers and isins from the TN API...')
        ->expectsOutput('Completed to get tickers and isins from the TN API.')
        ->assertExitCode(0);
});

test('get tickers and isins from the TN API with last date', function () {
    $serviceMock = mock(TnService::class)->makePartial();
    $serviceMock->shouldReceive('getActualDate')->andReturn('2024-02-08');

    $response = $serviceMock->getActualDate();
    Cache::add('last_tn_securities_date', $response);

    $this->artisan('tn-get:tickers-isins')
        ->expectsOutput('Get actual date for the screening')
        ->expectsOutput('The last date is actual. No need to update.')
        ->assertExitCode(0);
});

test('schedule command test', function () {
    $this->artisan('schedule:run')
        ->assertExitCode(0);
});

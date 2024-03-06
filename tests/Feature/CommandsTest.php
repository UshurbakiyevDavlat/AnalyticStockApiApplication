<?php

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

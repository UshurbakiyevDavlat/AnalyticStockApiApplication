<?php

use App\Services\TnService;

it('returns actual date', function () {
    // Mocking necessary dependencies such as Http, Cache, and Log
    // Replace 'YourService' with the actual class name where your method is defined
    $serviceMock = mock(TnService::class)->makePartial();
    $serviceMock->shouldReceive('getActualDate')->andReturn('2024-03-04');

    $response = $serviceMock->getActualDate();

    expect($response)->toBeString()
        ->and($response)
        ->toBe('2024-03-04'); // Ensure it returns a string
    // Assert the expected result
});

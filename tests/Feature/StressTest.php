<?php

use App\Enums\StressTestIntEnum;

use function Pest\Stressless\stress;

it('has a fast response time', function () {
    $result = stress(config('app.url'));

    expect(
        $result->requests()
            ->duration()
            ->med(),
    )
        ->toBeLessThan(
            StressTestIntEnum::REQUEST_DURATION->value,
        ); // < 200.00ms
});

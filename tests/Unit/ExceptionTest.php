<?php

use App\Enums\StatusCodeEnum;
use App\Exceptions\Handler;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

it('throttle exception render', function () {
    $exceptionThrottle = new ThrottleRequestsException('Too Many Attempts.');
    $exceptionLarge = new PostTooLargeException('Too Large.');

    $responseThrottle = app(Handler::class)->render(request(), $exceptionThrottle);
    $responseLarge = app(Handler::class)->render(request(), $exceptionLarge);

    $this->assertEquals($responseThrottle->getStatusCode(), StatusCodeEnum::TOO_MANY_REQUESTS->value);
    $this->assertEquals($responseLarge->getStatusCode(), StatusCodeEnum::PAYLOAD_TOO_LARGE->value);
});

<?php

namespace DrH\Buni\Tests\Unit;

use GuzzleHttp\Psr7\Response;

test('confirm environment is set to testing', function () {
    expect(config('app.env'))->toBe('testing');
});


test('logger', function () {
    expect(shouldBuniLog())->toBe(false);

    config()->set('buni.logging.enabled', true);

    expect(shouldBuniLog())->toBe(true);

    config()->set('buni.logging.channels', ['single']);

    buniLogError('test Logging Error');

    config()->set('buni.logging.channels', [
        [
            'driver' => 'single',
            'path' => '/dev/null',
        ]
    ]);

    buniLogInfo('test Logging Info');

    buniLog('warning', 'test Logging Warning');

});

test('parsing guzzle response', function () {
    expect(parseGuzzleResponse(new Response(headers: ['set-cookie' => true, 'asd' => 1])))->toBeArray()->not->toHaveKey('body')
        ->and(parseGuzzleResponse(new Response(), true))->toBeArray()->toHaveKey('body')
        ->and(parseGuzzleResponse(new Response(400)))->toBeArray()->toHaveKey('status_code', 400);
});

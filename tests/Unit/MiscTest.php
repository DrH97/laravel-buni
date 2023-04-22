<?php

namespace DrH\Buni\Tests\Unit;

test('confirm environment is set to testing', function () {
    expect(config('app.env'))->toBe('testing');
});


test('logger', function () {
    expect(shouldBuniLog())->toBe(false);

    config()->set('buni.logging.enabled', true);

    expect(shouldBuniLog())->toBe(true);

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

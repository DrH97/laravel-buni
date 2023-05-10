<?php


use DrH\Buni\Exceptions\BuniException;
use DrH\Buni\Library\Endpoints;
use Illuminate\Support\Facades\Config;

it('builds correct endpoints', function () {
    Config::set('buni.urls.base', 'http://localhost');

    $expectedEndpoint = 'http://localhost' . Endpoints::AUTH;

    $actualEndpoint = Endpoints::build(Endpoints::AUTH);

    expect($actualEndpoint)->toBe($expectedEndpoint);
});

it('throws on incorrect endpoints', function () {
    Endpoints::build('non-existent');
})->throws(BuniException::class, 'Endpoint is invalid or does not exist.');

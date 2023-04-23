<?php


use DrH\Buni\Exceptions\BuniException;
use DrH\Buni\Library\Authenticator;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Config;

it('can authenticate successfully', function () {
    $this->mock->append(
        new Response(200, ['Content_type' => 'application/json'],
            json_encode($this->mockResponses['auth']['success'])));

    expect((new Authenticator($this->baseClient))->authenticate())->toBeString();
});

it('can cache token', function () {
    $this->mock->append(
        new Response(200, ['Content_type' => 'application/json'],
            json_encode($this->mockResponses['auth']['success'])));

    expect((new Authenticator($this->baseClient))->authenticate())->toBeString();

    // Should not throw since mock queue is empty but no http request is made
    expect((new Authenticator($this->baseClient))->authenticate())->toBeString();

    $this->expectException(OutOfBoundsException::class);

    Config::set('buni.secret', 'different secret');

    expect((new Authenticator($this->baseClient))->authenticate())->toBeString();
});

it('throws on unset credentials', function () {
    Config::set('buni.key');

    expect((new Authenticator($this->baseClient))->authenticate())->toBeString();
})->throws(BuniException::class, 'Key/Secret is missing.');


it('throws on invalid credentials', function () {
    $this->mock->append(
        new Response(401, ['Content_type' => 'application/json'],
            json_encode($this->mockResponses['auth']['failed'])));

    dd(expect((new Authenticator($this->baseClient))->authenticate()));
})->throws(BuniException::class, 'Unauthorized');

it('throws on unexpected status', function () {
    $this->mock->append(
        new Response(500, ['Content_type' => 'application/json'],
            json_encode($this->mockResponses['auth']['failed'])));

    dd(expect((new Authenticator($this->baseClient))->authenticate()));
})->throws(BuniException::class);

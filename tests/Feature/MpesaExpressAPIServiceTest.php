<?php


use DrH\Buni\Exceptions\BuniException;
use DrH\Buni\Library\APIs\MpesaExpressAPIService;
use DrH\Buni\Models\BuniStkCallback;
use DrH\Buni\Models\BuniStkRequest;
use GuzzleHttp\Psr7\Response;
use function Pest\Laravel\postJson;


$stkCallbackUrl = '/buni/callbacks/stk';

it('sends stk push successfully', function () {
    $this->mock->append(
        new Response(200, ['Content_type' => 'application/json'],
            json_encode($this->mockResponses['auth']['success'])));
    $this->mock->append(
        new Response(200, ['Content_type' => 'application/json'],
            json_encode($this->mockResponses['stk']['request']['success'])));

    $response = (new MpesaExpressAPIService($this->baseClient))->push(1, '722000000', 'test');

    expect($response)->toBeInstanceOf(BuniStkRequest::class)->toHaveKey('phone_number', '254722000000');
});

it('throws on unsuccessful stk push', function () {
    $this->mock->append(
        new Response(200, ['Content_type' => 'application/json'],
            json_encode($this->mockResponses['auth']['success'])));
    $this->mock->append(
        new Response(200, ['Content_type' => 'application/json'],
            json_encode($this->mockResponses['stk']['request']['failed'])));

    (new MpesaExpressAPIService($this->baseClient))->push(1, '0722000000', 'test');

})->throws(BuniException::class, 'Invalid Credentials');


it('sends stk push and processes successful callback', function () use ($stkCallbackUrl) {
    $this->mock->append(
        new Response(200, ['Content_type' => 'application/json'],
            json_encode($this->mockResponses['auth']['success'])));
    $this->mock->append(
        new Response(200, ['Content_type' => 'application/json'],
            json_encode($this->mockResponses['stk']['request']['success'])));

    $request = (new MpesaExpressAPIService($this->baseClient))->push(1, '0722000000', 'test');

    postJson($stkCallbackUrl, $this->mockResponses['stk']['callback']['success'])
        ->assertSuccessful()
        ->assertJson(['status' => true]);

    expect($request->refresh())->toBeInstanceOf(BuniStkRequest::class)->toHaveKey('phone_number', '254722000000')
        ->and($request->callback)->toBeInstanceOf(BuniStkCallback::class)->toHaveKey('phone_number', '254722000000')
        ->and($request->status)->toBe('PAID');
});


it('sends stk push and processes failed callback', function () use ($stkCallbackUrl) {
    $this->mock->append(
        new Response(200, ['Content_type' => 'application/json'],
            json_encode($this->mockResponses['auth']['success'])));
    $this->mock->append(
        new Response(200, ['Content_type' => 'application/json'],
            json_encode($this->mockResponses['stk']['request']['success'])));

    $request = (new MpesaExpressAPIService($this->baseClient))->push(1, '0722000000', 'test');

    postJson($stkCallbackUrl, $this->mockResponses['stk']['callback']['failed'])
        ->assertSuccessful()
        ->assertJson(['status' => true]);

    expect($request->refresh())->toBeInstanceOf(BuniStkRequest::class)->toHaveKey('phone_number', '254722000000')
        ->and($request->callback)->toBeInstanceOf(BuniStkCallback::class)->toHaveKey('result_code', '-1')
        ->and($request->status)->toBe('FAILED');
});

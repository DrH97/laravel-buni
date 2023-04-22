<?php

namespace DrH\Buni\Tests\Database;

use DrH\Buni\Models\BuniStkCallback;
use DrH\Buni\Models\BuniStkRequest;

it('can run migrations', function () {

    BuniStkRequest::create([
        'phone_number' => '254722000000',
        'amount' => 70000,
        'invoice_number' => 'Test Case',
        'description' => 'My tests are running',
        'checkout_request_id' => 'ws_CO_02052018230213621',
        'merchant_request_id' => '10054-2753415-2'
    ]);

    $request = BuniStkRequest::first();

    expect($request->checkout_request_id)->toBe('ws_CO_02052018230213621');

    BuniStkCallback::create([
        'merchant_request_id' => 'test_merchant_req_id',
        'checkout_request_id' => 'test_checkout_req_id',
        'result_code' => '0',
        'result_desc' => 'Success',
    ]);

    $callback = BuniStkCallback::first();

    expect($callback->checkout_request_id)->toBe('test_checkout_req_id');
});


it('handles relationships', function () {
    $request = BuniStkRequest::create([
        'phone_number' => '254722000000',
        'amount' => 70000,
        'invoice_number' => 'Test Case',
        'description' => 'My tests are running',
        'checkout_request_id' => 'ws_CO_02052018230213621',
        'merchant_request_id' => '10054-2753415-2'
    ]);

    expect($request->callback)->toBeNull();

    $callback = BuniStkCallback::create([
        'merchant_request_id' => 'test_merchant_req_id',
        'checkout_request_id' => 'ws_CO_02052018230213621',
        'result_code' => '0',
        'result_desc' => 'Success',
    ]);

    expect($callback->request)->not->toBeNull();

    $request->load('callback');
    expect($request->callback)->not->toBeNull();
});

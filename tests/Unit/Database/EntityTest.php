<?php

namespace DrH\Buni\Tests\Database;

use DrH\Buni\Models\BuniIpn;
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

    BuniIpn::create([
        'transaction_reference' => 'test_tx_ref',
        'request_id' => 'test_req_id',
        'timestamp' => '20220404114325',
        'transaction_amount' => '0',
        'currency' => 'KES',
        'customer_reference' => 'Cash Deposit',
        'customer_name' => 'SHADRACK',
        'customer_mobile_number' => '',
        'narration' => 'Success',
        'credit_account_identifier' => '1147489750',
        'organization_short_code' => '9750',
        'till_number' => '',
    ]);

    $ipn = BuniIpn::first();

    expect($ipn->transaction_reference)->toBe('test_tx_ref');
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
        'merchant_request_id' => '10054-2753415-2',
        'checkout_request_id' => 'ws_CO_02052018230213621',
        'result_code' => '0',
        'result_desc' => 'Success',
    ]);

    expect($callback->request)->not->toBeNull();

    $request->load('callback');
    expect($request->callback)->not->toBeNull();
});

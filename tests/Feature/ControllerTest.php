<?php

use DrH\Buni\Models\BuniStkCallback;
use DrH\Buni\Models\BuniStkRequest;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;


$stkCallbackUrl = '/buni/stk-callback';

//beforeAll(function() { config()->set('buni.logging.enabled', true); });

it('fails to handle empty callback', function () use ($stkCallbackUrl) {
    postJson($stkCallbackUrl)->assertSuccessful()
        ->assertJson(['status' => true]);

    assertDatabaseCount((new BuniStkCallback())->getTable(), 0);
});

it('fails to handle malformed callback', function () use ($stkCallbackUrl) {
    postJson($stkCallbackUrl, [
        // Missing initiator reference
        "stkCallback" => [
            "MerchantRequestID" => "3789-53045504-1",
            "ResultCode" => 0,
            "ResultDesc" => "The service request is processed successfully.",
            "CallbackMetadata" => [
                "Item" => [[
                    "Name" => "Amount",
                    "Value" => 1.0
                ], [
                    "Name" => "MpesaReceiptNumber",
                    "Value" => "RD56FI0EGI"
                ], [
                    "Name" => "Balance"
                ], [
                    "Name" => "TransactionDate",
                    "Value" => 20230405093635
                ], [
                    "Name" => "PhoneNumber",
                    "Value" => 254722000000
                ]]
            ]
        ]
    ])->assertSuccessful()
        ->assertJson(['status' => true]);

    assertDatabaseCount((new BuniStkCallback())->getTable(), 0);
});

it('handles successful callback', function () use ($stkCallbackUrl) {

    BuniStkRequest::create([
        'phone_number' => '254722000000',
        'amount' => 70000,
        'invoice_number' => 'Test Case',
        'description' => 'My tests are running',
        'checkout_request_id' => 'ws_CO_02052018230213621',
        'merchant_request_id' => '10054-2753415-2'
    ]);

    postJson($stkCallbackUrl, [
        "stkCallback" => [
            "MerchantRequestID" => "3789-53045504-1",
            "CheckoutRequestID" => "ws_CO_05042023093636023714611696",
            "ResultCode" => 0,
            "ResultDesc" => "The service request is processed successfully.",
            "CallbackMetadata" => [
                "Item" => [[
                    "Name" => "Amount",
                    "Value" => 1.0
                ], [
                    "Name" => "MpesaReceiptNumber",
                    "Value" => "RD56FI0EGI"
                ], [
                    "Name" => "Balance"
                ], [
                    "Name" => "TransactionDate",
                    "Value" => 20230405093635
                ], [
                    "Name" => "PhoneNumber",
                    "Value" => 254722000000
                ]]
            ]
        ]
    ])->assertSuccessful()
        ->assertJson(['status' => true]);

    assertDatabaseCount((new BuniStkCallback())->getTable(), 1);

//    Event::assertDispatched(BuniStkRequestSuccessEvent::class, 1);
});

it('handles failed callback', function () use ($stkCallbackUrl) {
    BuniStkRequest::create([
        'phone_number' => '254722000000',
        'amount' => 70000,
        'invoice_number' => 'Test Case',
        'description' => 'My tests are running',
        'checkout_request_id' => 'ws_CO_02052018230213621',
        'merchant_request_id' => '10054-2753415-2'
    ]);

    assertDatabaseCount((new BuniStkRequest())->getTable(), 1);
    assertDatabaseHas((new BuniStkRequest())->getTable(), [
        'checkout_request_id' => 'ws_CO_02052018230213621',
    ]);

    postJson($stkCallbackUrl, [
        "stkCallback" => [
            "MerchantRequestID" => "3789-53045504-1",
            "CheckoutRequestID" => "ws_CO_05042023093636023714611696",
            "ResultCode" => 'ERR_CODE',
            "ResultDesc" => "The service request failed.",
        ]
    ])->assertSuccessful()
        ->assertJson(['status' => true]);

    assertDatabaseCount((new BuniStkCallback())->getTable(), 1);
    assertDatabaseHas((new BuniStkCallback())->getTable(), [
        'checkout_request_id' => 'ws_CO_05042023093636023714611696',
        'result_code' => '-1',
    ]);

//    Event::assertDispatched(TendePayRequestFailedEvent::class, 1);
});

<?php

use DrH\Buni\Events\BuniStkRequestFailedEvent;
use DrH\Buni\Events\BuniStkRequestSuccessEvent;
use DrH\Buni\Models\BuniStkCallback;
use DrH\Buni\Models\BuniStkRequest;
use Illuminate\Support\Facades\Event;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;


$stkCallbackUrl = '/buni/stk-callback';

it('fails to handle empty callback', function () use ($stkCallbackUrl) {
    postJson($stkCallbackUrl)->assertSuccessful()
        ->assertJson(['status' => true]);

    assertDatabaseCount((new BuniStkCallback())->getTable(), 0);
});

it('fails to handle malformed callback', function () use ($stkCallbackUrl) {
    postJson($stkCallbackUrl, [
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

//    dd($this->mockResponses['stk']['callback']['success']);

    postJson($stkCallbackUrl, $this->mockResponses['stk']['callback']['success'])
        ->assertSuccessful()
        ->assertJson(['status' => true]);

    assertDatabaseCount((new BuniStkCallback())->getTable(), 1);

    Event::assertDispatched(BuniStkRequestSuccessEvent::class, 1);
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

    postJson($stkCallbackUrl, $this->mockResponses['stk']['callback']['failed'])
        ->assertSuccessful()
        ->assertJson(['status' => true]);

    assertDatabaseCount((new BuniStkCallback())->getTable(), 1);
    assertDatabaseHas((new BuniStkCallback())->getTable(), [
        'checkout_request_id' => 'ws_CO_05042023093636023]',
        'result_code' => '-1',
        "result_desc" => 'The service request failed.' . ' - ' . 'ERR_CODE',
    ]);

    Event::assertDispatched(BuniStkRequestFailedEvent::class, 1);
});

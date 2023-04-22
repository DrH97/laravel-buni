<?php

use DrH\Buni\Models\BuniStkRequest;
use function Pest\Laravel\postJson;

it('handles successful callback', function () {
    BuniStkRequest::create([
        'phone_number' => '254722000000',
        'amount' => 70000,
        'invoice_number' => 'Test Case',
        'description' => 'My tests are running',
        'checkout_request_id' => 'ws_CO_02052018230213621',
        'merchant_request_id' => '10054-2753415-2'
    ]);

    postJson('/buni/stk-callback', [
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

//    assertDatabaseCount((new BuniStkCallback())->getTable(), 1);

//    Event::assertDispatched(BuniStkRequestSuccessEvent::class, 1);
});


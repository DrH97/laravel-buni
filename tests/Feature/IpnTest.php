<?php

use DrH\Buni\Events\BuniIpnEvent;
use DrH\Buni\Models\BuniIpn;
use Illuminate\Support\Facades\Event;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\postJson;


$ipnUrl = 'buni/callbacks/ipn';

//it('handles ipn endpoint', function () use ($ipnUrl) {
//    postJson($ipnUrl, ["requestId" => "a7e446ae-1f00-456f-9da1-fd418c091129",])->assertSuccessful()
//        ->assertJson($this->mockResponses['ipn']['response']);
//});
//
//
//it('fails to handle malformed ipn', function () use ($ipnUrl) {
//    postJson($ipnUrl, [
//        "requestId" => 'a7e446ae-1f00-456f-9da1-fd418c091129',
//        "ResultCode" => 0,
//        "ResultDesc" => "The service request is processed successfully.",
//        "CallbackMetadata" => [
//            "Item" => [[
//                "Name" => "Amount",
//                "Value" => 1.0
//            ], [
//                "Name" => "MpesaReceiptNumber",
//                "Value" => "RD56FI0EGI"
//            ], [
//                "Name" => "Balance"
//            ], [
//                "Name" => "TransactionDate",
//                "Value" => 20230405093635
//            ], [
//                "Name" => "PhoneNumber",
//                "Value" => 254722000000
//            ]]
//        ]
//    ])->assertSuccessful()
//        ->assertJson($this->mockResponses['ipn']['response']);
//
//    assertDatabaseCount((new BuniStkCallback())->getTable(), 0);
//});


it('handles successful ipn', function () use ($ipnUrl) {
    postJson($ipnUrl, $this->mockResponses['ipn']['request'])
        ->assertSuccessful()
        ->assertJson($this->mockResponses['ipn']['response']);

    assertDatabaseCount((new BuniIpn())->getTable(), 1);

    Event::assertDispatched(BuniIpnEvent::class, 1);
});


it('handles duplicate ipn', function () use ($ipnUrl) {
    postJson($ipnUrl, $this->mockResponses['ipn']['request'])
        ->assertSuccessful()
        ->assertJson($this->mockResponses['ipn']['response']);

    postJson($ipnUrl, $this->mockResponses['ipn']['request'])
        ->assertSuccessful()
        ->assertJson($this->mockResponses['ipn']['response']);

    assertDatabaseCount((new BuniIpn())->getTable(), 1);

    Event::assertDispatched(BuniIpnEvent::class, 1);

});

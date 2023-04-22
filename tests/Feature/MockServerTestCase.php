<?php

namespace DrH\Buni\Tests\Feature;

use DrH\Buni\Library\BaseClient;
use DrH\Buni\Library\Core;
use DrH\Buni\Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;

abstract class MockServerTestCase extends TestCase
{
    use RefreshDatabase;

    protected BaseClient $baseClient;

    protected MockHandler $mock;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('buni.key', 'somethinggoeshere');
        Config::set('buni.secret', 'somethinggoeshere');

        $this->mock = new MockHandler();

        $handlerStack = HandlerStack::create($this->mock);
        $this->baseClient = new BaseClient(new Client(['handler' => $handlerStack]));

        $this->core = new Core($this->baseClient);
    }

    protected array $mockResponses = [
        'auth' => [
            'success' => [
                "access_token" => "aleXRXEEePvzGRDBfmONSYnAw",
                "token_type" => "Bearer",
                "expires_in" => 3600
            ],
            'failed' => [
                "error_description" => "A valid OAuth client could not be found for client_id: 4dN8NMh_j1fIxOlyfaxlSnfcCCa",
                "error" => "invalid_client"
            ],
        ],
        'stk' => [
            'request' => [
                'success' => [
                    "response" => [
                        "MerchantRequestID" => "24457-234847396-1",
                        "ResponseCode" => "0",
                        "CustomerMessage" => "Success. Request accepted for processing",
                        "CheckoutRequestID" => false,
                        "ResponseDescription" => "Success. Request accepted for processing"
                    ],
                    "header" => [
                        "statusDescription" => "Success. Request accepted for processing",
                        "statusCode" => "0"
                    ]
                ],
                'failed' => [
                    "fault" => [
                        "code" => 900901,
                        "message" => "Invalid Credentials",
                        "description" => "Invalid Credentials. Make sure you have given the correct access token"
                    ]
                ],
            ],
            'callback' => [
                'success' => [
                    "stkCallback" => [
                        "MerchantRequestID" => "3789-53045504-1",
                        "CheckoutRequestID" => "ws_CO_05042023093636023]",
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
                ],
                'failed' => [
                    "stkCallback" => [
                        "MerchantRequestID" => "3789-53045504-1",
                        "CheckoutRequestID" => "ws_CO_05042023093636023]",
                        "ResultCode" => 'ERR_CODE',
                        "ResultDesc" => 'The service request failed.',
                    ]
                ],
            ],
        ],
    ];
}

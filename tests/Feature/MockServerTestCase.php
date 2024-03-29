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
                        "MerchantRequestID" => "3789-53045504-1",
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
                'failed2' => [
                    "response" => [],
                    "header" => [
                        "statusDescription" => "STK Push Backend Posting Failure: Bad Request - Invalid PhoneNumber",
                        "statusCode" => "1"
                    ]
                ]
            ],
            'callback' => [
                'success' => [
                    "Body" => [
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
                    ]
                ],
                'failed' => [
                    "Body" => [
                        "stkCallback" => [
                            "MerchantRequestID" => "3789-53045504-1",
                            "CheckoutRequestID" => "ws_CO_05042023093636023]",
                            "ResultCode" => 'ERR_CODE',
                            "ResultDesc" => 'The service request failed.',
                        ]
                    ]
                ],
            ],
        ],
        'ipn' => [
            'request' => [
                "transactionReference" => "TT22094VLK98",
                "requestId" => "a7e446ae-1f00-456f-9da1-fd418c091129",
                "channelCode" => "null",
                "timestamp" => "20220404114325",
                "transactionAmount" => "101",
                "currency" => "KES",
                "customerReference" => "Cash Deposit",
                "customerName" => "SHADRACK KIPLANGAT KIRUI",
                "customerMobileNumber" => "",
                "balance" => "",
                "narration" => "TEST",
                "creditAccountIdentifier" => "1147489750",
                "organizationShortCode" => "9750",
                "tillNumber" => ""
            ],
            'response' => [
                'transactionID' => 'a7e446ae-1f00-456f-9da1-fd418c091129',
                'statusCode' => '0',
                'statusMessage' => 'Notification received'
            ]
        ]
    ];
}

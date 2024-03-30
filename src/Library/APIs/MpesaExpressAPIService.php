<?php

namespace DrH\Buni\Library\APIs;

use DrH\Buni\Exceptions\BuniException;
use DrH\Buni\Library\Core;
use DrH\Buni\Library\Endpoints;
use DrH\Buni\Models\BuniStkRequest;
use GuzzleHttp\Exception\GuzzleException;

class MpesaExpressAPIService extends Core
{

    /**
     * @throws GuzzleException
     * @throws BuniException
     */
    public function push(
        int    $amount,
        string $phone,
        string $reference,
        string $description = ''
    ): BuniStkRequest
    {
        $callback = config('buni.urls.stk_callback');

        $phone = $this->formatPhoneNumber($phone);

        $body = [
            'phoneNumber' => $phone,
            'amount' => $amount,
            'invoiceNumber' => $reference,
            'sharedShortCode' => true,
            'orgShortCode' => "",
            'orgPassKey' => "",
            'callbackUrl' => $callback,
            'transactionDescription' => $description,
        ];

        $response = $this->request(Endpoints::STK_REQUEST, $body);

        return $this->saveRequest($body, $response);
    }

    /**
     * @throws BuniException
     */
    private function saveRequest(array $body, array $response): BuniStkRequest
    {
        if (!isset($response['response'])) {
            throw new BuniException($response['fault']['message']);
        }

        $header = $response['header'];
        $response = $response['response'];

        if ($header['statusCode'] == 0) {
            $data = [
                'phone_number' => $body['phoneNumber'],
                'amount' => $body['amount'],
                'invoice_number' => $body['invoiceNumber'],
                'description' => $body['transactionDescription'],
                'checkout_request_id' => $response['CheckoutRequestID'],
                'merchant_request_id' => $response['MerchantRequestID'],
            ];
            return BuniStkRequest::create($data);
        }
        throw new BuniException($response['ResponseDescription'] ?? $header['statusDescription']);
    }

}

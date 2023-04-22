<?php

namespace DrH\Buni\Library;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BaseClient
{
    public Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @throws GuzzleException
     */
    public function sendRequest(string $method, string $url, array $headers = [], array $body = []): array
    {
        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                ...$headers
            ],
            'json' => $body,
        ];

        $response = $this->httpClient->request(
            $method,
            $url,
            $options
        );
        buniLogInfo('response: ', parseGuzzleResponse($response));

        return json_decode($response->getBody(), true);
    }
}

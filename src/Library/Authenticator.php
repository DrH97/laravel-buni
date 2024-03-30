<?php

namespace DrH\Buni\Library;

use DrH\Buni\Exceptions\BuniException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class Authenticator
{
    private ?string $credentials = null;

    private string $endpoint;

    /**
     * @throws BuniException
     */
    public function __construct(private readonly BaseClient $client)
    {
        $key = config('buni.key', false);
        $secret = config('buni.secret', false);

        if (!$key || !$secret) {
            throw new BuniException("Key/Secret is missing.");
        }

        $this->credentials = base64_encode($key . ':' . $secret);
        $this->endpoint = Endpoints::build(Endpoints::AUTH);
    }

    /**
     * @throws BuniException
     */
    public function authenticate(): string
    {
        if (config('buni.cache_credentials', true) && !empty($key = $this->getFromCache())) {
            return $key;
        }

        try {
            $response = (object)$this->makeRequest();

            $this->saveCredentials($response);

            return $response->access_token;

        } catch (GuzzleException $exception) {
            buniLogError('authErr', json_decode($exception->getResponse()?->getBody(), true) ?? []);

            $message = $exception->getResponse() ?
                $exception->getResponse()->getReasonPhrase() :
                $exception->getMessage();

            throw new BuniException($message);
        }
    }

    /**
     * @throws GuzzleException
     */
    private function makeRequest(): array
    {
        return $this->client->sendRequest(
            'POST',
            $this->endpoint,
            [
                'Authorization' => 'Basic ' . $this->credentials,
                'Content-Type' => '',
            ]
        );
    }

    private function getFromCache(): mixed
    {
        return Cache::get('buni:' . $this->credentials);
    }

    private function saveCredentials(object $credentials): void
    {
//        Use the returned expiry time with 10 seconds leeway for latency etc...
        Cache::put('buni:' . $this->credentials, $credentials->access_token, $credentials->expires_in - 10);
    }
}

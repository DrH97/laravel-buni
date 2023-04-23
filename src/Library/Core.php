<?php

namespace DrH\Buni\Library;

use DrH\Buni\Exceptions\BuniException;
use GuzzleHttp\Exception\GuzzleException;

class Core
{

    private Authenticator $authenticator;

    /**
     * @throws BuniException
     */
    public function __construct(private readonly BaseClient $baseClient)
    {
        $this->authenticator = new Authenticator($this->baseClient);
    }

    /**
     * @throws GuzzleException
     * @throws BuniException
     */
    public function request(string $endpointSuffix, array $body): array
    {
        $endpoint = Endpoints::build($endpointSuffix);

        buniLogInfo("request: ", [$endpoint, $body]);
        $response = $this->baseClient->sendRequest('POST', $endpoint, $this->getBearerHeader(), $body);
        buniLogInfo('body: ', $response);

        return $response;
    }

    /**
     * @throws BuniException
     */
    private function getBearerHeader(): array
    {
        $bearer = $this->authenticator->authenticate();

        return ['Authorization' => 'Bearer ' . $bearer];
    }

}

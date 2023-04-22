<?php

namespace DrH\Buni\Library;

use DrH\Buni\Exceptions\BuniException;
use GuzzleHttp\Exception\GuzzleException;

class Core
{

    public function __construct(private readonly BaseClient $baseClient)
    {
    }

    /**
     * @throws GuzzleException
     * @throws BuniException
     */
    public function request(string $endpointSuffix, array $body): array
    {
        $endpoint = Endpoints::build($endpointSuffix);

        buniLogInfo("request: ", [$endpoint, $body]);
        $response = $this->baseClient->sendRequest('POST', $endpoint, $body);
        buniLogInfo('body: ', $response);

        return $response;
    }

}

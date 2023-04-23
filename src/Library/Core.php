<?php

namespace DrH\Buni\Library;

use DrH\Buni\Exceptions\BuniException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;

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

    public function formatPhoneNumber(string $number, bool $strip_plus = true): string
    {
        $number = preg_replace('/\s+/', '', $number);
        $replace = static function ($needle, $replacement) use (&$number) {
            if (Str::startsWith($number, $needle)) {
                $pos = strpos($number, $needle);
                $length = strlen($needle);
                $number = substr_replace($number, $replacement, $pos, $length);
            }
        };
        $replace('2547', '+2547');
        $replace('07', '+2547');
        $replace('2541', '+2541');
        $replace('01', '+2541');
        $replace('7', '+2547');
        $replace('1', '+2541');
        if ($strip_plus) {
            $replace('+254', '254');
        }
        return $number;
    }

}

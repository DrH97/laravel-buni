<?php

namespace DrH\Buni\Library;

use DrH\Buni\Exceptions\BuniException;

class Endpoints
{
    public const AUTH = '/token?grant_type=client_credentials';
    public const STK_REQUEST = '/mm/api/request/1.0.0/stkpush';

    public const ENDPOINT_REQUEST_TYPES = [
        self::AUTH => 'POST',
        self::STK_REQUEST => 'POST',
    ];

    /**
     * @throws BuniException
     */
    public static function build(string $endpoint): string
    {
        return self::getEndpoint($endpoint);
    }

    /**
     * @throws BuniException
     */
    private static function getEndpoint(string $suffix): string
    {
        if (!in_array($suffix, array_keys(self::ENDPOINT_REQUEST_TYPES))) {
            throw new BuniException("Endpoint is invalid or does not exist.");
        }

        $defaultProductionUrl = 'https://api.buni.kcbgroup.com';
        $defaultSandboxUrl = 'https://uat.buni.kcbgroup.com';

        $defaultUrl = config('buni.sandbox') ? $defaultSandboxUrl : $defaultProductionUrl;

        $baseEndpoint = rtrim(
            config('buni.urls.base', $defaultUrl),
            '/'
        );

        return $baseEndpoint . $suffix;
    }


}

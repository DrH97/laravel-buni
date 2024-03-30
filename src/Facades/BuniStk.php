<?php

namespace DrH\Buni\Facades;

use DrH\Buni\Library\APIs\MpesaExpressAPIService;
use DrH\Buni\Models\BuniStkRequest;
use Illuminate\Support\Facades\Facade;

/**
 * @method static BuniStkRequest push(int $amount, string $phone, string $reference, string $description = '')
 *
 * @see \DrH\Buni\Library\APIs\MpesaExpressAPIService
 */
class BuniStk extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MpesaExpressAPIService::class;
    }
}

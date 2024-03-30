<?php

namespace DrH\Buni\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * DrH\Buni\Models\BuniStkRequest
 *
 * @property int $id
 * @property string $phone_number
 * @property float $amount
 * @property string $invoice_number
 * @property boolean $shared_short_code
 * @property int $org_short_code
 * @property string $org_pass_key
 * @property string $description
 * @property string $status
 * @property string $merchant_request_id
 * @property string $checkout_request_id
 * @property int|null $relation_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read BuniStkCallback $callback
 *
 */
class BuniStkRequest extends Model
{
    protected $guarded = [];

    public function callback(): HasOne
    {
        // TODO: confirm why checkout request is not returned in initial request
        return $this->hasOne(BuniStkCallback::class, 'merchant_request_id', 'merchant_request_id');
    }
}

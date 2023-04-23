<?php

namespace DrH\Buni\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * DrH\Buni\Models\BuniStkCallback
 *
 * @property int $id
 * @property string $merchant_request_id
 * @property string $checkout_request_id
 * @property int $result_code
 * @property string $result_desc
 * @property float|null $amount
 * @property string|null $mpesa_receipt_number
 * @property float|null $balance
 * @property string|null $phone
 * @property string|null $transaction_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read BuniStkRequest $request
 *
 */
class BuniStkCallback extends Model
{
    protected $guarded = [];

    public function request(): BelongsTo
    {
        return $this->belongsTo(BuniStkRequest::class, 'merchant_request_id', 'merchant_request_id');
    }
}

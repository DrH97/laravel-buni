<?php

namespace DrH\Buni\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * DrH\Buni\Models\BuniIpn
 *
 * @property int $id
 * @property string $transaction_reference
 * @property string $request_id
 * @property string timestamp
 * @property float|null $transaction_amount
 * @property string|null $customer_reference
 * @property string|null $customer_name
 * @property float|null $balance
 * @property string|null $customer_mobile_number
 * @property string|null $till_number
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 */
class BuniIpn extends Model
{
    protected $guarded = [];

}

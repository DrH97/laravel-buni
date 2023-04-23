<?php

namespace DrH\Buni\Events;

use DrH\Buni\Models\BuniStkCallback;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BuniStkRequestSuccessEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public readonly BuniStkCallback $callback, public readonly array $data)
    {
        buniLogInfo('BuniStkRequestSuccessEvent: ', $callback->toArray());

        $callback->request()->update(['status' => 'PAID']);
    }
}

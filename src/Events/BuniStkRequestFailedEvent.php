<?php

namespace DrH\Buni\Events;

use DrH\Buni\Models\BuniStkCallback;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BuniStkRequestFailedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public readonly BuniStkCallback $request, public readonly array $callback)
    {
        buniLogInfo('BuniRequestFailedEvent: ', [$request, $callback]);
    }
}

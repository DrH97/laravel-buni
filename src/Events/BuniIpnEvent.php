<?php

namespace DrH\Buni\Events;

use DrH\Buni\Models\BuniIpn;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BuniIpnEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public readonly BuniIpn $ipn)
    {
        buniLogInfo('BuniIpnEvent: ', $ipn->toArray());
    }
}

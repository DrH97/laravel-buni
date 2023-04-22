<?php

namespace DrH\Buni\Http;

use DrH\Buni\Models\BuniStkCallback;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Controller extends \Illuminate\Routing\Controller
{
    public function handleStkCallback(Request $request): JsonResponse
    {
        buniLogInfo('STK CB: ', $request->all());

        try {
            $data = (object)$request->stkCallback;

            $callback = [
                'merchant_request_id' => $data->MerchantRequestID,
                'checkout_request_id' => $data->CheckoutRequestID,
                'result_code' => $data->ResultCode,
                'result_desc' => $data->ResultDesc ?? $data->ResultCode,
            ];

            if ($data->ResultCode == 0) {
                $meta = $data->CallbackMetadata['Item'];
                foreach ($meta as $item) {
                    $callback[Str::snake($item['Name'])] = @$item['Value'];
                }
            } elseif (!is_numeric($data->ResultCode)) {
                $callback['result_code'] = -1;
                $callback['result_desc'] .= ' - ' . $data->ResultCode;
            }

            $stkRequest = BuniStkCallback::create($callback);

//            $event = $callback->status == 1 ?
//                new BuniStkRequestSuccessEvent($stkRequest, $data) :
//                new BuniStkRequestFailedEvent($stkRequest, $data);

//            event($event);
        } catch (Exception $e) {
            buniLogError('Error handling callback. - ' . $e->getMessage(), $e->getTrace());
        }

        return response()->json(['status' => true]);
    }
}

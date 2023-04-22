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

        // TODO: Should we separate handling of Non-Existent request,
        //  duplicate callback and catch-all Exceptions separately?
        try {
            $data = $request->stkCallback;

            $callback = [
                'merchant_request_id' => $data->MerchantRequestID,
                'checkout_request_id' => $data->CheckoutRequestID,
                'result_code' => $data->ResultCode,
                'result_desc' => $data->ResultDesc ?? $data->ResultCode,
            ];

            if ($data->ResultCode == 0) {
                $meta = $data->CallbackMetadata->Item;
                foreach ($meta as $item) {
                    if ($item->Name == "PhoneNumber") {
                        $callback['phone'] = @$item->Value;
                    } else {
                        $callback[Str::snake($item->Name)] = @$item->Value;
                    }
                }
            } elseif (!is_numeric($data->ResultCode)) {
                // TODO: make this a more general rule for string codes
                $real_data['result_code'] = -1;
                $real_data['result_desc'] .= ' - ' . $data->ResultCode;
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

<?php

namespace DrH\Buni\Http;

use DrH\Buni\Events\BuniIpnEvent;
use DrH\Buni\Events\BuniStkRequestFailedEvent;
use DrH\Buni\Events\BuniStkRequestSuccessEvent;
use DrH\Buni\Models\BuniIpn;
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
            $data = (object)$request->Body['stkCallback'];

            if (BuniStkCallback::whereMerchantRequestId($data->MerchantRequestID)->exists()) {
                throw new Exception('callback exists');
            }

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

            $stkCallback = BuniStkCallback::create($callback);

            $event = $stkCallback->result_code == 0 ?
                new BuniStkRequestSuccessEvent($stkCallback, $request->Body['stkCallback']) :
                new BuniStkRequestFailedEvent($stkCallback, $request->Body['stkCallback']);

            event($event);
        } catch (Exception $e) {
            buniLogError('Error handling callback: ' . $e->getMessage(), $e->getTrace());
        }

        return response()->json(['status' => true]);
    }


    public function handleIpn(Request $request): JsonResponse
    {
        buniLogInfo('IPN: ', $request->all());

        try {
            $data = (object)$request->all();

            if (BuniIpn::whereRequestId($data->requestId)->exists()) {
                throw new Exception('ipn already received');
            }

            $ipnData = [];

            foreach ($data as $key => $value) {
                $ipnData[Str::snake($key)] = @$value;
            }

            $ipn = BuniIpn::create($ipnData);

            event(new BuniIpnEvent($ipn));
        } catch (Exception $e) {
            buniLogError('Error handling ipn: ' . $e->getMessage(), $e->getTrace());
        }

        return response()->json([
            'transactionID' => $request->requestId, // TODO: confirm its not requestId
            'statusCode' => '0',
            'statusMessage' => 'Notification received'
        ]);
    }
}

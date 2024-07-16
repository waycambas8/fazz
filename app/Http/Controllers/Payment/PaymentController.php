<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Service\ConnectServer;
use App\Service\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function Retail(Request $request)
    {
        $Model = $request->Payload->get('Model');
        $Data = ConnectServer::ServiceConnection(
            'service-fazz',
            ''
        )->post(
            [
                "data" => [
                    "attributes" => [
                        "paymentMethodType" => $Model->paymentMethodType,
                        "amount" => $Model->amount,
                        "currency" => $Model->currency,
                        "referenceId" => $Model->referenceId,
                        "expiredAt" => $Model->expiredAt,
                        "description" => "",
                        "paymentMethodOptions" => [
                            "retailOutletName" => $Model->retailOutletName,
                            "displayName" => $Model->displayName
                        ]
                    ]
                ]
            ],
            "/payments",
            true
        );

        if (isset($Data['data'])) {
            $Data['data'] = $this->ArrayMerge($Data, $Model);
        }

        return response()->json(
            JsonResponse::get(
                '',
                $Data,
                Response::HTTP_ACCEPTED
            )
        );
    }

    public function VirtualAccount(Request $request)
    {
        $Model = $request->Payload->get('Model');
        $Data = ConnectServer::ServiceConnection(
            'service-fazz',
            ''
        )->post(
            [
                "data" => [
                    "attributes" => [
                        "paymentMethodType" => $Model->paymentMethodType,
                        "amount" => $Model->amount,
                        "currency" => $Model->currency,
                        "referenceId" => $Model->referenceId,
                        "expiredAt" => $Model->expiredAt,
                        "description" => "",
                        "paymentMethodOptions" => [
                            "bankShortCode" => $Model->retailOutletName,
                            "displayName" => $Model->displayName
                        ]
                    ]
                ]
            ],
            "/payments",
            true
        );

        if (isset($Data['data'])) {
            $Data['data'] = $this->ArrayMerge($Data, $Model);
        }

        return response()->json(
            JsonResponse::get(
                '',
                $Data,
                Response::HTTP_ACCEPTED
            )
        );
    }


    private function ArrayMerge($Data, $Model)
    {
        $Trxid = ['trxid' => $Model->trxid];
        return array_merge($Data['data'], $Trxid);
    }
}

<?php

namespace App\Http\Controllers\Method;

use App\Http\Controllers\Controller;
use App\Service\ConnectServer;
use App\Service\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MethodController extends Controller
{
    public function StaticVirtualAccount(Request $request)
    {
        $Model = $request->Payload->get('Model');
        $Data = ConnectServer::ServiceConnection(
            'service-fazz',
            ''
        )->post(
            [
                "data" => [
                    "attributes" => [
                        "bankShortCode" => $Model->bankShortCode,
                        "referenceId" => $Model->referenceId,
                        "displayName" => $Model->displayName,
                        "suffixNo" => $Model->suffixNo,
                    ]
                ]
            ],
            "/payment_methods/virtual_bank_accounts",
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

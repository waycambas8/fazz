<?php

namespace App\Http\Controllers\Method;

use App\Http\Controllers\Controller;
use App\Service\ConnectServer;
use App\Service\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MethodBrowseController extends Controller
{
    public function List()
    {
        $Data = ConnectServer::ServiceConnection(
            'service-fazz',
            ''
        )->get(
            [],
            "/payment_methods/virtual_bank_accounts",
            true
        );

        return response()->json(
            JsonResponse::get(
                '',
                $Data,
                Response::HTTP_ACCEPTED
            )
        );
    }

    public function Detail(Request $request)
    {
        $Data = ConnectServer::ServiceConnection(
            'service-fazz',
            ''
        )->get(
            [],
            "/payment_methods/virtual_bank_accounts/$request->uuid/payments",
            true
        );

        return response()->json(
            JsonResponse::get(
                '',
                $Data,
                Response::HTTP_ACCEPTED
            )
        );
    }
}

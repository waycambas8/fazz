<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Service\ConnectServer;
use App\Service\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentBrowseController extends Controller
{
    public function List()
    {
        $Data = ConnectServer::ServiceConnection(
            'service-fazz',
            ''
        )->get(
            [],
            "/payments",
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

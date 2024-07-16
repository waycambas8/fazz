<?php

namespace App\Http\Middleware\Payment;

use App\Service\JsonResponse;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class VirtualAccount
{
    protected $Model, $Service, $Collection, $Message, $Payload;
    protected function Initiate($_Request)
    {
        $this->Model = (object)[];
        $this->Model->referenceId = $_Request->trxid;
        $this->Model->currency = 'IDR';
        $this->Model->paymentMethodType = 'virtual_bank_account';
        $this->Model->amount = $_Request->nominal;
        $this->Model->expiredAt = Carbon::tomorrow();
        $this->Model->retailOutletName = $_Request->bank;
        $this->Model->displayName = 'JPM';
        $this->Model->trxid = $_Request->trxid;
    }

    protected function Validation($_Request)
    {
        $Validator = Validator::make($_Request->all(), [
            "nominal" => ['required', 'integer'],
            "trxid" => ['required', 'string'],
            "bank" => ['required', 'string'],
        ]);
        if ($Validator->fails()) {
            foreach ($Validator->errors()->toArray() as $error) {
                foreach ($error as $sub_error) {
                    $err[] = $sub_error;
                }
            }
            $this->Message = $err[0];
            return false;
        }
        return true;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $this->Initiate($request);
        if ($this->Validation($request)) {
            $this->Payload = collect([]);
            $this->Payload->put('Model', $this->Model);
            $request->merge(['Payload' => $this->Payload]);
            return $next($request);
        } else {
            return response()->json(JsonResponse::get(
                '',
                $this->Message,
                Response::HTTP_NOT_ACCEPTABLE
            ), Response::HTTP_NOT_ACCEPTABLE);
        }
    }
}

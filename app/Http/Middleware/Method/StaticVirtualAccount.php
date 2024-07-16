<?php

namespace App\Http\Middleware\Method;

use App\Service\JsonResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class StaticVirtualAccount
{
    protected $Model, $Service, $Collection, $Message, $Payload;
    protected function Initiate($_Request)
    {
        $this->Model = (object)[];
        $this->Model->referenceId = $_Request->trxid;
        $this->Model->suffixNo = $_Request->norek;
        $this->Model->displayName = $_Request->nama;
        $this->Model->bankShortCode = $_Request->bank;
        $this->Model->trxid = $_Request->trxid;
    }

    protected function Validation($_Request)
    {
        $Validator = Validator::make($_Request->all(), [
            "trxid" => ['required', 'string'],
            "bank" => ['required', 'string'],
            "norek" => ['required', 'string'],
            "nama" => ['required', 'string'],
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

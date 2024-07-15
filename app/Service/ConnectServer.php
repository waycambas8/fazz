<?php

namespace App\Service;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Mockery\Matcher\Type;
use Symfony\Component\HttpFoundation\Response;

class ConnectServer
{
    protected $ServiceJson;
    protected $Service;
    protected $Authorization, $Message;

    public function __construct($Service, $Authorization)
    {
        $this->ServiceJson = config('connection.service') ?? false;
        $this->Service = collect($this->ServiceJson)->filter(function ($Value, $Key) use ($Service) {
            return ($Key === $Service) ?? $Value;
        });
        $this->Authorization = (isset($Authorization)) ? $Authorization : '';
    }

    public function get($_Request, $Path = null, $Auth = false)
    {
        if (!$this->ServiceJson) {
            $this->Message = __('messages.fail_service');
            return false;
        }
        if (!$this->Service) {
            $this->Message = __('messages.fail_service');
            return false;
        }
        $Service = collect($this->Service);

        return $this->RequestServer('GET', $Service, $_Request, $Path, $Auth);
    }

    public function post($_Request, $Path = null, $Auth = false)
    {
        if (!$this->ServiceJson) {
            $this->Message = __('messages.fail_service');
            return false;
        }
        if (!$this->Service) {
            $this->Message = __('messages.fail_service');
            return false;
        }
        $Service = collect($this->Service);

        return $this->RequestServer('POST', $Service, $_Request, $Path, $Auth);
    }

    public function put($_Request, $Path = null)
    {
        if (!$this->ServiceJson) {
            $this->Message = __('messages.fail_service');
            return false;
        }
        if (!$this->Service) {
            $this->Message = __('messages.fail_service');
            return false;
        }
        $Service = collect($this->Service);

        return $this->RequestServer('PUT', $Service, $_Request, $Path);
    }

    public function delete($_Request, $Path = null)
    {
        if (!$this->ServiceJson) {
            $this->Message = __('messages.fail_service');
            return false;
        }
        if (!$this->Service) {
            $this->Message = __('messages.fail_service');
            return false;
        }
        $Service = collect($this->Service);

        return $this->RequestServer('DELETE', $Service, $_Request, $Path);
    }

    private function RequestServer($Type, $Service, $_Request = [], $Path = null, $Auth = false)
    {

        $Header = [];
        if ($Auth) {
            $Header = [
                'accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode(\config('auth.bri_client') . ':' . \config('auth.bri_client_secret'))
            ];
        } else {
            $Header = [
                'accept' => 'application/json',
                'publicAccessToken' => 'Bearer ' . $this->Authorization
            ];
        }

        try {
            $Config = [
                'headers' => $Header,
                'json' => $_Request
            ];

            //dump($this->Authorization, $Config);
            $Client = new Client();
            $Response = $Client->request($Type, $Service->pluck('endpoint')->first() . $Path, $Config);
            $Data = json_decode($Response->getBody()->getContents(), true);
            return $Data;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $Data = json_decode($e->getResponse()->getBody()->getContents(), true);
            $Data['status'] = $e->getCode();
            return $Data;
        }
    }

    private function StatusCodeHandling($Response)
    {
        $StatusCode = $Response->getStatusCode();
        return $StatusCode;
    }

    public static function ServiceConnection($service, $auth)
    {
        return new ConnectServer($service, $auth);
    }
}

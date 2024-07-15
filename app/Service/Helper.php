<?php
namespace App\Service;

class Helper
{
    public static function GetSaldo()
    {
        $Data = ConnectServer::ServiceConnection(
            'service-bri',  
            self::Login()
        )->get(
            [], 
            'gs/balance',
            false
        );

        return (isset($Data['data']['brickPay']['balance'])) ? $Data['data']['brickPay']['balance'] : null;
    }

    public static function Login() 
    {
        $AccessToken = ConnectServer::ServiceConnection(
            'service-bri',
            ''
        )->get(
            [],
            'auth/token',
            true
        );

        if (isset($AccessToken['data']['accessToken'])) {
            $AccessToken = $AccessToken['data']['accessToken'];
        }

        return $AccessToken;
    }

    public static function MergeBalance($Data, $Model = null) 
    {
        $Saldo = [
            'trxid' => ($Model->trxid) ?? null,
            'saldo' => self::GetSaldo()
        ];
        return array_merge($Data, $Saldo);
    }
}
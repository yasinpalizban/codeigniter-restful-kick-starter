<?php

namespace Modules\Shared\Libraries;

use Modules\Shared\Interfaces\SmsInterface;

class Sms implements SmsInterface
{
    private string $username;
    private string $password;
    private string $phoneNumber;

    function __construct(string $username,string $password,string $phoneNumber)
    {
        $this->username = $username;
        $this->password = $password;
        $this->phoneNumber = $phoneNumber;
    }


    function sendSms(string $message, string $rec, int $sms): int
    {
        $Url = "http://smspanel.Trez.ir/SendMessageWithPost.ashx";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query(array('Username' => $this->username,
                'Password' => $this->password,
                'PhoneNumber' => $this->phoneNumber,
                'MessageBody' => $message,
                'RecNumber' => $rec,
                'Smsclass' => $sms)));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);

        return $server_output;

    }

    function sendActivationCode(string $mobileNumber,string $footer): int
    {
        $Url = "http://smspanel.Trez.ir/AutoSendCode.ashx";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query(array('Username' => $this->username,
                'Password' => $this->password,
                'Mobile' => $mobileNumber,
                'Footer' => $footer)));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);
        return $server_output;

    }

    function isActivationCodeValid(string $mobileNumber,string $code): bool
    {
        $Url = "http://smspanel.Trez.ir/CheckSendCode.ashx";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query(array('Username' => $this->username,
                'Password' => $this->password,
                'Mobile' => $mobileNumber,
                'Code' => $code)));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);
        return $server_output;

    }

    function sendCustomSms(string  $mobileNumber,string $message): int
    {
        $Url = "http://smspanel.Trez.ir/SendMessageWithCode.ashx";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            http_build_query(array('Username' => $this->username,
                'Password' => $this->password,
                'Mobile' => $mobileNumber,
                'Message' => $message)));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec($ch);

        curl_close($ch);
        return $server_output;

    }

}
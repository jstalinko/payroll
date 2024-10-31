<?php

namespace App;

class Helper
{
    protected static $PIWAPI_SECRETKEY = "";
    protected static  $PIWAPI_APIKEY = "";
    public static function sendWhatsapp($to_number, $message)
    {
        $number = preg_replace("/^08/","628",$to_number);
        $number = str_replace([' ','-'],"",$number);

        $chat = [
            "secret" => self::$PIWAPI_APIKEY, // your API secret from (Tools -> API Keys) page
            "account" => self::$PIWAPI_SECRETKEY,
            "recipient" => $number,
            "type" => "text",
            "message" => $message
        ];

        $cURL = curl_init("https://piwapi.com/api/send/whatsapp");
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_POSTFIELDS, $chat);
        $response = curl_exec($cURL);
        curl_close($cURL);

        $result = json_decode($response, true);

        return $result;
    }
}

<?php

namespace App;

use Illuminate\Support\Facades\Log;

class Helper
{
    protected static $PIWAPI_SECRETKEY = "173036684301386bd6d8e091c2ab4c7c7de644d37b67234d7bc360d";
    protected static  $PIWAPI_APIKEY = "4eb8af9236916dbe778bf5333b5ebd2f2d6afeb2";
    public static function sendWhatsapp($to_number, $message,$file)
    {
        $number = preg_replace("/^08/","628",$to_number);
        $number = str_replace([' ','-'],"",$number);

        $chat = [
            "secret" => self::$PIWAPI_APIKEY, // your API secret from (Tools -> API Keys) page
            "account" => self::$PIWAPI_SECRETKEY,
            "recipient" => $number,
            "type" => "document",
            "message" => $message,
            "document_type"=>"pdf",
            "document_url"=> url('storage/'.basename($file)),
            "document_name" => basename($file)
        ];

        $cURL = curl_init("https://piwapi.com/api/send/whatsapp");
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL , CURLOPT_POST,true);
        curl_setopt($cURL, CURLOPT_POSTFIELDS, $chat);
        $response = curl_exec($cURL);
        curl_close($cURL);

        $result = json_decode($response, true);
        file_put_contents('xx.txt',$response);
        return $result;
    }
}

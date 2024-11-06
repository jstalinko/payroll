<?php

namespace App;

use Illuminate\Support\Facades\Log;

class Helper
{
    protected static $PIWAPI_SECRETKEY = "173036684301386bd6d8e091c2ab4c7c7de644d37b67234d7bc360d";
    protected static  $PIWAPI_APIKEY = "4eb8af9236916dbe778bf5333b5ebd2f2d6afeb2";
    public static function sendWhatsapp($to_number, $message,$file , $custom_path = null)
    {
        $number = preg_replace("/^08/","628",$to_number);
        $number = str_replace([' ','-'],"",$number);
        if($custom_path != null)
        {
            $doc_url = url('storage/'.$custom_path.'/'.basename($file));
        }else{
            $doc_url = url('storage/'.basename($file));
        }

        $chat = [
            "secret" => self::$PIWAPI_APIKEY, // your API secret from (Tools -> API Keys) page
            "account" => self::$PIWAPI_SECRETKEY,
            "recipient" => $number,
            "type" => "document",
            "message" => $message,
            "document_type"=>"pdf",
            "document_url"=> $doc_url,
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
   public static function messageTemplate($data)
    {
        $t = "*SLIP GAJI " . config('app.setting.site_name') . "*\n";
        $t .= "\n\n";
        $t .= "NAMA : " . $data->karyawan->name . "\n";
        $t .= "JABATAN : " . $data->karyawan->position . "\n";
        $t .= "No. HP : " . $data->karyawan->phone . "\n";
        $t .= "------------------------------------\n";
        $t .= "*PENGHASILAN*\n";
        $t .= "*GAJI POKOK* : " . number_format($data->in_gaji_pokok, 0, ',', '.') . "\n";
        $t .= "*UPAH LEMBUR* : " . number_format($data->in_upah_lembur, 0, ',', '.') . "\n";
        $t .= "*UANG MAKAN* : " . number_format($data->in_uang_makan, 0, ',', '.') . "\n";
        $t .= "*UANG TRANSPORT* : " . number_format($data->in_uang_transport, 0, ',', '.') . "\n";
        $t .= "*LAIN-LAIN (" . $data->in_keterangan . ")* : " . number_format($data->in_lain, 0, ',', '.') . "\n";
        $t .= "------------------------------------\n";
        $t .= "*TOTAL PENGHASILAN* : " . number_format($data->in_gaji_pokok + $data->in_upah_lembur + $data->in_uang_makan + $data->in_uang_transport + $data->in_lain, 0, ',', '.') . "\n\n";
    
    
        $t .= "*POTONGAN*\n";
        $t .= "*TELAT* : " . number_format($data->out_telat, 0, ',', '.') . "\n";
        $t .= "*KERUSAKAN BARANG* : " . number_format($data->out_kerusakan_barang, 0, ',', '.') . "\n";
        $t .= "*KASBON* : " . number_format($data->out_kasbon, 0, ',', '.') . "\n";
        $t .= "*LAIN-LAIN (" . $data->out_keterangan . ")* : " . number_format($data->out_lain, 0, ',', '.') . "\n";
        $t .= "*TRANSPORT* :  ".number_format($data->out_uang_transport,0,',','.')."\n";
        $t .= "------------------------------------\n";
        $t .= "*TOTAL POTONGAN* : " . number_format($data->out_telat + $data->out_kerusakan_barang + $data->out_kasbon + $data->out_lain+$data->out_uang_transport, 0, ',', '.') . "\n\n";
    
        $t .= "*GAJI BERSIH* : " . number_format(($data->in_gaji_pokok + $data->in_upah_lembur + $data->in_uang_makan + $data->in_uang_transport + $data->in_lain) - ($data->out_telat + $data->out_kerusakan_barang + $data->out_kasbon + $data->out_lain + $data->out_uang_transport), 0, ',', '.') . "\n";
        $t.="\n\n\n";
        $t.= "GAJI SUDAH DI TRANSFER KE *(".$data->karyawan->bank_name.") ".$data->karyawan->account_number." a/n ".$data->karyawan->account_name."*";
    
        return $t;
    }
}

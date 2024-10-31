<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Models\Slip;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

class PrintSlipController extends Controller
{
    public function __invoke(Request $request)
    {
        $slip_id = $request->slip_id;
        $slip = Slip::findOrFail($slip_id);

        Helper::sendWhatsapp($slip->karyawan->phone,$this->messageTemplate($slip));
        $filename = 'SLIP-GAJI_'.str_replace(' ','_',$slip->karyawan->name).'.pdf';
         return Pdf::view('slipgaji' , ['data' => $slip,'multi' => false])->name($filename)->format('a5');
    }

    public function messageTemplate($data)
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
        $t .= "------------------------------------\n";
        $t .= "*TOTAL POTONGAN* : " . number_format($data->out_telat + $data->out_kerusakan_barang + $data->out_kasbon + $data->out_lain, 0, ',', '.') . "\n\n";
    
        $t .= "*GAJI BERSIH* : " . number_format(($data->in_gaji_pokok + $data->in_upah_lembur + $data->in_uang_makan + $data->in_uang_transport + $data->in_lain) - ($data->out_telat + $data->out_kerusakan_barang + $data->out_kasbon + $data->out_lain), 0, ',', '.') . "\n";
    
        return $t;
    }
}

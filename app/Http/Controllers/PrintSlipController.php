<?php

namespace App\Http\Controllers;

use App\Models\Slip;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;

class PrintSlipController extends Controller
{
    public function __invoke(Request $request)
    {
        $slip_id = $request->slip_id;
        $slip = Slip::findOrFail($slip_id);

        $filename = 'SLIP-GAJI_'.str_replace(' ','_',$slip->karyawan->name).'.pdf';
         return Pdf::view('slipgaji' , ['data' => $slip,'multi' => false])->name($filename)->format('a5');
    }
}

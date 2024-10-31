<?php

use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrintSlipController;
use App\Http\Controllers\JustOrangeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [JustOrangeController::class, 'index']);
Route::get('/view', function (Request $request) {
   if ($request->view) {
      return view('slipgaji',['data' => \App\Models\Slip::find(1)]);
   } else {
      return Pdf::view('slipgaji')->format('a4')
         ->save('invoice.pdf',['data'=> \App\Models\Slip::find(1)]);
   }
});

Route::get('/print-payroll', PrintSlipController::class);

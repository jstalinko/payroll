<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'name',
        'position',
        'phone',
        'address',
        'salary',
        'payment_method',
        'account_name',
        'bank_name',
        'account_number'
    ];
}

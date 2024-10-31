<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slip extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'karyawan_id', 
        'period_start',
        'period_end',
        'in_gaji_pokok',
        'in_upah_lembur',
        'in_uang_makan',
        'in_uang_transport',
        'in_lain',
        'in_keterangan',
        'out_telat',
        'out_kerusakan_barang',
        'out_kasbon',
        'out_lain',
        'out_keterangan', 
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'detail' => 'array'
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }
}
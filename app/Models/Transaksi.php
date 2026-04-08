<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi'; // pastikan sama dengan nama tabel di database

    protected $fillable = [
        'kendaraan_id',
        'user_id',
        'tarif_id',
        'area_id',
        'waktu_masuk',
        'waktu_keluar',
        'durasi_jam',
        'biaya_bayar',
        'status'
    ];
}

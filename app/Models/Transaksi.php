<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

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

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function area()
    {
        return $this->belongsTo(AreaParkir::class, 'area_id');
    }
}

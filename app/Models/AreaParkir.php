<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaParkir extends Model
{
    protected $table = 'area_parkir';

    protected $fillable = [
        'nama_area',
        'kapasitas',
        'terisi'
    ];

    protected $attributes = [
        'terisi' => 0
    ];

    protected $casts = [
        'kapasitas' => 'integer',
        'terisi' => 'integer'
    ];

    public function getSisaAttribute()
    {
        return $this->kapasitas - $this->terisi;
    }

    public function getStatusAttribute()
    {
        return $this->terisi >= $this->kapasitas ? 'penuh' : 'tersedia';
    }

    public function getStatusColorAttribute()
    {
        if ($this->terisi >= $this->kapasitas) {
            return 'danger';
        } elseif ($this->terisi > ($this->kapasitas / 2)) {
            return 'warning';
        }
        return 'success';
    }

    
}

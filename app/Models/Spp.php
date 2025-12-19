<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spp extends Model
{
    use HasFactory;
    protected $table = 'spp';
    protected $guarded = ['id'];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'spp_id');
    }

    public function anggota_kelas()
    {
        return $this->belongsTo(Anggota_Kelas::class, 'anggota_kelas_id');
    }
}

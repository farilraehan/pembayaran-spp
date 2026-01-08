<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;
    protected $table = 'siswa';
    protected $guarded = ['id'];

    public function getTahunAkademik()
    {
        return $this->belongsTo(Tahun_akademik::class, '');
    }

    public function getKelas()
    {
        return $this->belongsTo(Kelas::class, '');
    }

    public function getAnggotaKelas()
    {
        return $this->hasMany(Anggota_Kelas::class, 'id_siswa', 'id');
    }
    public function getTransaksi()
    {
        return $this->hasMany(Transaksi::class, 'siswa_id');
    }
}

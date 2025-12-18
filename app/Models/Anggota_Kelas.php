<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota_Kelas extends Model
{
    use HasFactory;
    protected $table = 'anggota_kelas';
    protected $guarded = ['id'];

    public function getTahunAkademik()
    {
        return $this->belongsTo(Tahun_Akademik::class,'tahun_akademik','nama_tahun');
    }

    public function getKelas()
    {
        return $this->belongsTo(Kelas::class,'kode_kelas','kode_kelas');
    }

    public function getSiswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }
    public function getSpp()
    {
        return $this->hasMany(Spp::class, 'anggota_kelas');
    }
}

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
        return $this->belongsTo(Tahun_Akademik::class, 'tahun_akademik_id');
    }
    public function getKelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
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

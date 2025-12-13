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
        return $this->belongsTo(Anggota_Kelas::class, '');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keuangan_nominal extends Model
{
    use HasFactory;
    protected $table = 'keuangan_nominal';
    protected $guarded = ['id'];

    public function getkeuanganJenis()
    {
        return $this->belongsTo(Keuangan_jenis::class, 'id_keuangan_jenis', 'id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    use HasFactory;
    protected $table = 'rekening';
    protected $guarded = [];

    public function transaksiDebit()
    {
        return $this->hasMany(
            Transaksi::class,
            'rekening_debit',   
            'kode_akun'         
        );
    }

    public function transaksiKredit()
    {
        return $this->hasMany(
            Transaksi::class,
            'rekening_kredit',
            'kode_akun'
        );
    }
    
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'rekening_debit', 'kode_akun')
            ->orWhere('rekening_kredit', $this->kode_akun);
    }
   
}

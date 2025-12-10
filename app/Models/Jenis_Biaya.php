<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jenis_Biaya extends Model
{
    use HasFactory;
    protected $table = 'jenis_biaya';
    protected $guarded = ['id'];


    public function get_rekening()
    {
        return $this->belongsTo(Rekening::class, '');
    }
}

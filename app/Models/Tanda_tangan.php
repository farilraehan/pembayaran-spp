<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanda_tangan extends Model
{
    use HasFactory;
    protected $table = 'tanda_tangan';
    protected $guarded = ['id'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prin_t extends Model
{
    
    protected $table = 'print';
    protected $fillable = ['type','address','phone','line1','line2','icon'];
}

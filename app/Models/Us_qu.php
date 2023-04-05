<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Us_qu extends Model
{
    protected $table = 'user_query';
    protected $fillable = ['m_year','id_sys','type','amount','date','created_by','slug','box_id','name'];
}

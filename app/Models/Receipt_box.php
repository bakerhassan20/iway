<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt_box extends Model
{
    //
    protected $table = 'receipt_boxes';
    protected $fillable = ['id','id_comp','id_sys','type','m_year','date','amount','notes','box_id','isdelete','created_by','updated_by','deleted_by'];
}

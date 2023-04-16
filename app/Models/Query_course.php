<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query_course extends Model
{
    protected $table = 'query_course';
    protected $fillable = ['title','year','total_register','total_withdrawn','all_graduate'];
}

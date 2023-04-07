<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query_email extends Model
{
    protected $table = 'query_email';
    protected $fillable = ['name','email','type'];
}

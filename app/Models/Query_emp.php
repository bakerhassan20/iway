<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query_emp extends Model
{
    protected $table = 'query_emp';
    protected $fillable = ['name','absence','late','all_late','reward','all_reward','reward1','all_reward1'];
}


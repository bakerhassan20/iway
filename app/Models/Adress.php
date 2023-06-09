<?php

namespace App\Models;

use App\Models\Teacher;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Adress extends Model
{
    use HasFactory;

    public function employee(){
        return $this->hasMany(Employee::class,'addres_id');
    }

    public function teacher(){
        return $this->hasMany(Teacher::class,'addres_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSalaryModel extends Model
{
    use HasFactory;
    
    protected $table = 'teacher_salary';
    protected $fillable = ['id','name','status'];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryModel extends Model
{
    use HasFactory;
    
    protected $table = 'employee_salary';
    protected $fillable = ['id','name','status'];
}

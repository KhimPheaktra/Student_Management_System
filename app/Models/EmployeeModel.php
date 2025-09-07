<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model
{
    use HasFactory;

    protected $table = 'employees';
    protected $fillable = ['id','first_name','last_name','position_id','dob','gender_id','pob','phone','salary_id','image','status'];


    public function position_id(){
        return $this->belongsTo(PositionModel::class , 'postion_id');
    }
 

}

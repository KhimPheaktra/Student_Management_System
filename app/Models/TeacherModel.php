<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherModel extends Model
{
    use HasFactory;

    protected $table = 'teachers';

    protected $fillable = ['id','first_name','last_name','dob','pob','gender_id','salary_id','position_id','image','status'];


    // public function genderId(){
    //     return $this->belongsTo(GenderModel::class,'gender_id','id');
    // }

    
    // public function pob(){
    //     return $this->belongsTo(ProvinceModel::class,'pob','id');
    // }
    
}

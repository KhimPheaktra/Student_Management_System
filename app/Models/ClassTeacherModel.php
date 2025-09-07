<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassTeacherModel extends Model
{
    use HasFactory;

    protected $table = "class_teachers";
    protected $fillable = ['id','class_id','teacher_id','subject_id'];

    public function teacher()
{
    return $this->belongsTo(TeacherModel::class, 'teacher_id');
}

}

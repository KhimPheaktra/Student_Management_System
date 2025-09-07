<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentModel extends Model
{
    use HasFactory;

    protected $table = "students";

    protected $fillable = ['id','first_name','last_name','dob','pob','phone','parent_phone','image','enroll_at','major_id','gen_id','status'];

    public function major()
    {
        return $this->belongsTo(MajorModel::class, 'major_id');
    }

    /**
     * Get the generation that the student belongs to.
     */
    public function generation()
    {
        return $this->belongsTo(GenerationModel::class, 'gen_id');
    }

    /**
     * Get the subject scores for the student.
     */
    public function subjectScores()
    {
        return $this->hasMany(SubjectScoreModel::class, 'student_id');
    }

    /**
     * Get the term grades for the student.
     */
    public function termGrades()
    {
        return $this->hasMany(TermGradeModel::class, 'student_id');
    }

}



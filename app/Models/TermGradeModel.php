<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermGradeModel extends Model
{
    use HasFactory;
    protected $table = 'term_grades';
    protected $fillable = ['id','student_id','term_id','gen_id','average_score','grade'];

    public function term_id()
    {
        return $this->belongsTo(TermModel::class);
    }

    public function student_id()
    {
        return $this->belongsTo(StudentModel::class);
    }
}

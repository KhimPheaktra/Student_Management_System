<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectScoreModel extends Model
{
    use HasFactory;
    
    protected $table = 'subject_scores';
    protected $fillable = ['id', 'student_id', 'subject_id', 'term_id','gen_id' ,'midterm','final','total', 'grade'];
    
    public function term()
    {
        return $this->belongsTo(TermModel::class, 'term_id');
    }
    
    public function subject()
    {
        return $this->belongsTo(SubjectModel::class, 'subject_id');
    }
    
    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_id');
    }

}

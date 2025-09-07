<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermModel extends Model
{
    use HasFactory;

    protected $table = 'terms';
    protected $fillable = ['id','name','start_date','end_date','gen_id'];

    public function termGrades()
    {
        return $this->hasMany(TermGradeModel::class);
    }
    public function subjects()
    {
        return $this->hasMany(SubjectModel::class);
    }
}

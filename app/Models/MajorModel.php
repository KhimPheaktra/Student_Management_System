<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MajorModel extends Model
{
    use HasFactory;
    protected $table = 'majors';
    protected $fillable = ['id','name','status'];

    public function students()
    {
        return $this->hasMany(StudentModel::class, 'major_id');
    }

    /**
     * Get the subjects that belong to the major.
     */
    public function subjects()
    {
        return $this->hasMany(SubjectModel::class, 'major_id');
    }

}

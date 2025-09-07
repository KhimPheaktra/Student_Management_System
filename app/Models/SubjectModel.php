<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectModel extends Model
{
    use HasFactory;

    protected $table = 'subjects';
    protected $fillable = ['id','name','full_score','status','term_id'];
    
    public function term_id()
{
    return $this->belongsTo(TermModel::class);
}

}

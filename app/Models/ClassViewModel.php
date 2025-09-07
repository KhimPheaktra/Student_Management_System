<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassViewModel extends Model
{
    use HasFactory;

    protected $table = 'class_view';
    protected $fillable = ['id','name','status','total_teacher'];
}

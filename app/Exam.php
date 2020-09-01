<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'id', 'name', 'grades_id','students_id', 'status'
    ];
}

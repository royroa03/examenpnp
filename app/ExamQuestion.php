<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamQuestion extends Model
{
    protected $table = "exams_questions";

    protected $fillable = [
        'id', 'exams_id', 'questions_id', 'alternative', 'name_alternative', 'is_correct','response','status'
    ];

}

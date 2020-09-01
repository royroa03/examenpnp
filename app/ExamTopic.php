<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamTopic extends Model
{
    protected $table = "exams_topics";

    protected $fillable = [
        'id', 'exams_id', 'topics_id', 'status'
    ];

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'id', 'description', 'review', 'exams_topics_id', 'status'
    ];
}

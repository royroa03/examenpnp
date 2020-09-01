<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    protected $table = "alternatives";

    protected $fillable = [
        'id', 'questions_id', 'alternative', 'name_alternative','is_correct','response'
    ];

}

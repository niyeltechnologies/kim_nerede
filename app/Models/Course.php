<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
  use SoftDeletes, HasFactory;
   
      protected $fillable = [
        'user_id',
        'course_name',
        'course_topic',
        'instructor_phone'
    ];
}

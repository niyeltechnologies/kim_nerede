<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentCourseStatement extends Model
{
     use SoftDeletes, HasFactory;

  protected $fillable = [
        'student_course_id',
        'course_date',
        'course_time',
        'amount_due',
        'attended_duration',
        'attended'
    ];

    public function student_course()
    {
        return $this->belongsTo(StudentCourse::class);
    }
}

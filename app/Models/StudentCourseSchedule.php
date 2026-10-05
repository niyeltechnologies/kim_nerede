<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentCourseSchedule extends Model
{
    use SoftDeletes, HasFactory;

      protected $fillable = [
        'student_course_id',
        'day_of_week',
        'start_time',
        'duration',
    ];

    public function student_course()
    {
        return $this->belongsTo(StudentCourse::class);
    }
}

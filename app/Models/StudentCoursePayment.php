<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentCoursePayment extends Model
{
     use SoftDeletes, HasFactory;

      protected $fillable = [
        'student_course_id',
        'payment_date',
        'payment_amount',
    ];

    public function student_course()
    {
        return $this->belongsTo(StudentCourse::class);
    }
}

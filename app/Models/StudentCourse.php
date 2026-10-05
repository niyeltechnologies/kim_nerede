<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentCourse extends Model
{
   use SoftDeletes, HasFactory;

   protected $fillable = [
        'student_detail_id',
        'course_id',
        'course_price',
        'course_price_type',
    ];

    public function student_detail()
    {
        return $this->belongsTo(StudentDetail::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

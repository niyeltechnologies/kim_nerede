<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentDetail extends Model
{
      use SoftDeletes, HasFactory;

      protected $fillable = [
        'student_name',
        'student_surname',
        'student_owner',
        'display_color'
    ];

      public function student_detail()
    {
        return $this->belongsTo(StudentDetail::class);
    }
}

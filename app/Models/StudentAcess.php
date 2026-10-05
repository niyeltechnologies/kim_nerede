<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAcess extends Model
{
     use SoftDeletes, HasFactory;

      protected $fillable = [
        'user_id',
        'student_detail_id',
    ];

    public function student_detail()
    {
        return $this->belongsTo(StudentDetail::class);
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

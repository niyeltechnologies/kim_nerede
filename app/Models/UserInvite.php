<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInvite extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'user_email',
        'student_detail_id',
        'invited_by',
        'invite_code'
    ];

    public function student_detail()
    {
        return $this->belongsTo(StudentDetail::class);
    }

}

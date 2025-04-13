<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRequest extends Model
{
    protected $fillable = [
        'user_id',
        'attendance_id',
        'target_column',
        'old_value',
        'new_value',
        'reason',
        'status',
    ];

    // 紐づくユーザー
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 紐づく勤怠データ
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}


<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    //登録可能なカラムを指定する
    protected $fillable = [
        'user_id', 'date', 'clock_in',  'clock_out', 'note'
    ];

    //ユーザーとリレーション（多対一） attendances テーブルには user_id という外部キーがあり、それを使って users テーブルと関連付ける ということ
    public function user() {
        return $this->belongsTo(User::class);
    }

     //ユーザーとリレーション（多対一） breaktime テーブルには user_id という外部キーがあり、それを使って users テーブルと関連付ける ということ
    public function breakTimes(){
        return $this->hasMany(BreakTime::class);
    }

    public function requests()
{
    return $this->hasMany(AttendanceRequest::class);
}
}

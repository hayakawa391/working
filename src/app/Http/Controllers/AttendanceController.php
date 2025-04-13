<?php

namespace App\Http\Controllers;

//Attendanceモデルを読み込む
use App\Models\Attendance;
//BreakTimeモデルを読み込む
use App\Models\BreakTime;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; //認証されたユーザー情報を取得する

use App\Models\AttendanceEditRequest; //出退勤の変更を読み込む

class AttendanceController extends Controller
{
    //出退勤の履歴を表示
     public function index() {
        // ログイン中のユーザーの出退勤のデータを取得
         $today = now()->toDateString();
        $attendance = Attendance::where('user_id', Auth::id())->where('date', $today)->with('breakTimes')->first();                //↑休憩情報を一緒に取得する

        return view('user.index', compact('attendance'));
    }

    //出勤ボタンの処理
    public function clockIn() {
        $today = now()->toDateString(); // 今日の日付を取得

        // すでに出勤記録がある場合は更新しない
        $attendance = Attendance::firstOrCreate(
            ['user_id' => Auth::id(), 'date' => $today], // ユーザーIDと日付が一致するデータを検索
            ['clock_in' => now()->toTimeString()] // なければ出勤時間を記録
        );

        return redirect()->back()->with('success', '出勤を記録しました');
    }

    // 退勤ボタン処理
    public function clockOut() {
        $today = now()->toDateString(); // 今日の日付を取得
        $attendance = Attendance::where('user_id', Auth::id())->where('date', $today)->first();

        if (!$attendance) {
        return redirect()->back()->with('error', '出勤記録がありません');
        }

        if ($attendance->clock_out) {
        return redirect()->back()->with('error', 'すでに退勤済みです');
        }

        $attendance->update(['clock_out' => now()->toTimeString()]);
        return redirect()->back()->with('success', '退勤を記録しました');
    }


  // 休憩開始
public function startBreak()
{
    $attendance = Attendance::where('user_id', Auth::id())->where('date', now()->toDateString())->first();

    if (!$attendance) {
        return back()->with('error', '出勤記録がありません');
    }

    $existingBreak = BreakTime::where('attendance_id', $attendance->id)
        ->whereNull('break_end')
        ->latest()
        ->first();

    if ($existingBreak) {
        return back()->with('error', 'すでに休憩中です');
    }

    BreakTime::create([
        'attendance_id' => $attendance->id,
        'break_start' => now()->toTimeString(),
    ]);

    return back()->with('success', '休憩を開始しました！');
}

//休憩終わり
public function endBreak()
{
    $attendance = Attendance::where('user_id', Auth::id())->where('date', now()->toDateString())->first();

    if (!$attendance) {
        return back()->with('error', '出勤記録がありません');
    }

    $break = BreakTime::where('attendance_id', $attendance->id)
        ->whereNull('break_end')
        ->latest()
        ->first();

    if (!$break) {
        return back()->with('error', '休憩開始が記録されていません');
    }

    $break->update(['break_end' => now()->toTimeString()]);

    return back()->with('success', '休憩を終了しました！');
}

//月次勤怠ページの表示
public function monthly(Request $request)
{
    $user = Auth::user();
    $currentMonth = $request->input('month') ?? now()->format('Y-m');

    $attendances = Attendance::with('breakTimes')
        ->where('user_id', $user->id)
        ->where('date', 'like', "$currentMonth%")
        ->orderBy('date')
        ->get();

    return view('user.monthly', compact('attendances', 'currentMonth'));
}

//各日付の詳細ページの表示
public function detail($id)
{
    $attendance = Attendance::with('breakTimes')->findOrFail($id);
    return view('user.detail', compact('attendance'));
}

//コントローラーに編集画面と保存機能を追加
public function editNote($id)
{
    $attendance = Attendance::findOrFail($id);
    return view('user.note_edit', compact('attendance'));
}

public function updateNote(Request $request, $id)
{
    $attendance = Attendance::findOrFail($id);
    $attendance->note = $request->input('note');
    $attendance->save();

    return redirect()->route('attendance.detail', $id)->with('success', '備考を更新しました。');
}

 // ログインユーザーの全出退勤データを取得（降順）
 public function history()
 {
    $attendances = Attendance::where('user_id', Auth::id())
                             ->orderBy('date', 'desc')
                             ->with('breakTimes') // 休憩データも取得
                             ->get();

    return view('user.history', compact('attendances'));
 }
//出退勤の変更を行う
public function requestEdit(Request $request, $id)
{
    $request->validate([
        'new_clock_in' => 'nullable|date_format:H:i',
        'new_clock_out' => 'nullable|date_format:H:i|after:new_clock_in',
        'reason' => 'required|string|max:255',
    ]);

    $attendance = Attendance::findOrFail($id);

    // すでに申請がある場合はエラー
    if (AttendanceEditRequest::where('attendance_id', $id)->where('status', 'pending')->exists()) {
        return back()->with('error', '現在、申請中の変更があります。');
    }

    AttendanceEditRequest::create([
        'user_id' => auth()->id(),
        'attendance_id' => $attendance->id,
        'new_clock_in' => $request->new_clock_in,
        'new_clock_out' => $request->new_clock_out,
        'reason' => $request->reason,
    ]);

    return back()->with('success', '変更申請を送信しました。管理者の承認をお待ちください。');
}
}
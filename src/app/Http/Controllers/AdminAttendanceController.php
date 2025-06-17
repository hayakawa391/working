<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\Attendance;
use App\Models\AttendanceRequest;
use App\Models\User;

class AdminAttendanceController extends Controller
{
     // 出退勤データの一覧表示（全ユーザー分）
    public function index()
    {
        $attendances = Attendance::with('user')->orderBy('date', 'desc')->get();
        return view('admin.attendance.index', compact('attendances'));
    }

    public function editAttendanceEditRequests()
{
    $requests = AttendanceEditRequest::where('status', 'pending')->get();
    return view('admin.attendance.requests', compact('requests'));
}


public function rejectRequest($id)
{
    AttendanceEditRequest::findOrFail($id)->update(['status' => 'rejected']);
    return back()->with('error', '変更申請を拒否しました。');
}

//コントローラーに月次・日別表示ロジックを追加to
public function showMonthly($id, Request $request)
{
    $user = User::findOrFail($id);

    $month = $request->query('month', Carbon::now()->format('Y-m'));
    $startOfMonth = Carbon::parse($month)->startOfMonth();
    $endOfMonth = Carbon::parse($month)->endOfMonth();

    $attendances = Attendance::where('user_id', $id)
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->orderBy('date', 'asc')
        ->get();

    return view('admin.users.monthly_attendance', compact('user', 'attendances', 'month'));
}

public function showDaily($id, $date)
{
    $user = User::findOrFail($id);

    $attendance = Attendance::where('user_id', $id)
        ->where('date', $date)
        ->firstOrFail();

    return view('admin.users.daily_attendance', compact('user', 'attendance'));
}

// 承認待ち一覧
public function editWorkingHourRequests()
{
    $pendingRequests = AttendanceRequest::where('status', 'pending')->with('user')->get();
    return view('admin.requests.pending', compact('pendingRequests'));
}

// 承認済み一覧
public function approvedRequests()
{
    $approvedRequests = AttendanceRequest::where('status', 'approved')->with('user')->get();
    return view('admin.requests.approved', compact('approvedRequests'));
}

// 詳細ページ
public function requestDetail($id)
{
    $request = AttendanceRequest::with('user', 'attendance')->findOrFail($id);
    return view('admin.requests.detail', compact('request'));
}

// 承認処理
public function approveRequest($id)
{
    $request = AttendanceRequest::findOrFail($id);

    // 勤怠情報の更新
    $attendance = $request->attendance;
    $attendance->update([
        'clock_in'    => $request->new_clock_in,
        'clock_out'   => $request->new_clock_out,
        'break_start' => $request->new_break_start,
        'break_end'   => $request->new_break_end,
    ]);

    // ステータス更新
    $request->status = 'approved';
    $request->save();

    return redirect()->route('admin.attendance.requests')->with('success', '申請を承認しました');
}

//----------------------------------------------------------
//CSVエクスポート機能を追加時に書いた
public function exportMonthlyAttendance($userId)
{
    $user = \App\Models\User::findOrFail($userId);
    $month = request('month', now()->format('Y-m')); // 例: 2025-04

    // 該当月の開始日と終了日を取得
    $startOfMonth = Carbon::parse($month)->startOfMonth();
    $endOfMonth = Carbon::parse($month)->endOfMonth();

    // 該当ユーザーの月次勤怠を取得
    $attendances = Attendance::where('user_id', $userId)
        ->whereBetween('date', [$startOfMonth, $endOfMonth])
        ->get();

    // CSVヘッダー
    $csvHeader = [
        '日付', '出勤時間', '退勤時間', '休憩開始', '休憩終了', '備考'
    ];

    // CSVデータ
    $csvData = $attendances->map(function ($attendance) {
        return [
            $attendance->date,
            $attendance->clock_in,
            $attendance->clock_out,
            $attendance->break_start,
            $attendance->break_end,
            $attendance->note ?? '',
        ];
    });

    // 出力内容を文字列にする
    $callback = function () use ($csvHeader, $csvData) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, $csvHeader);

        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
    };

    $fileName = $user->name . "_attendance_" . $month . ".csv";

    return Response::stream($callback, 200, [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename={$fileName}",
    ]);
}

public function requests()
{
    // 承認待ちの勤怠修正申請を取得（関連ユーザーと勤怠も含める）
    $pendingRequests = AttendanceRequest::where('status', 'pending')->with(['user', 'attendance'])->get();

    // Bladeビューへ渡す
    return view('admin.requests.pending', compact('pendingRequests'));
}



}

<?php

use Illuminate\Support\Facades\Route;
// Laravelの認証機能を使用するためのクラスをインポートする
use Illuminate\Support\Facades\Auth;

//トップページ作成時に追加
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\UserAuthController;

//ログイン後に、出退勤の記録をするためのAttendance類のルーティング
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminAttendanceController;

//ユーザーを管理者に設定する
use App\Http\Controllers\UserController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



//Fortify によってログイン画面は /login にある
Route::get('/', fn () => redirect('/login'));



// ↓ログイン後は常に/attendanceへという仕様にする（管理者用は別で用意するのがよい）

//ログインのためにユーザー一覧のルートを追加する
Route::get('/users', [UserAuthController::class, 'user_index']) -> name('user.index');

//ログイン画面表示用
Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserAuthController::class, 'login']);



Route::middleware('auth')->group(function(){
    //auth ミドルウェアを適用したルートグループを作成しています。
    //auth ミドルウェアは、ユーザーが認証されているかどうかを確認するための仕組み。
    //このグループ内のすべてのルートは、認証されていない場合アクセスできない。

    //Laravelでは、ログインに成功すると「ログイン後にどこにリダイレクトするか？」が自動的に /dashboard に設定されているケースが多いです（特に Fortify, Breeze, Jetstream などの認証パッケージを使っている場合）
    //ユーザーがログイン成功 → /dashboard に自動リダイレクト
    //dashboard → /attendance に即座にリダイレクト
    //結果的に、ユーザーは /attendance（= 勤怠画面）にたどりつく
    Route::get('/dashboard', fn () => redirect()->route('attendance.index'))->name('dashboard');


    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
    Route::post('/attendance/break-start', [AttendanceController::class, 'startBreak'])->name('attendance.break-start');
    Route::post('/attendance/break-end', [AttendanceController::class, 'endBreak'])->name('attendance.break-end');

    //出退勤と休憩の一覧を見るためのルート
    Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
     Route::post('attendance/request-edit/{id}', [AttendanceController::class, 'requestEdit'])->name('attendance.edit-requests');

     //月次勤怠ページの表示
     Route::get('/attendance/monthly', [AttendanceController::class, 'monthly'])->name('admin.attendance.monthly');

     //各日付の詳細ページの表示
     Route::get('/attendance/detail/{id}', [AttendanceController::class, 'detail'])->name('attendance.detail');

     //コントローラーに編集画面と保存機能を追加
     Route::get('/attendance/{id}/note', [AttendanceController::class, 'editNote'])->name('attendance.note.edit');
    Route::put('/attendance/{id}/note', [AttendanceController::class, 'updateNote'])->name('attendance.note.update');

    

});


// -------------------------------
// 管理者ログイン関係

Route::get('/admin', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// 管理者専用ルート（auth + adminミドルウェア）
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    //Route::get('/dashboard', fn () => view('admin.dashboard'));とかいてもOK！中身がreturnの一行の時にだけ使える短縮形

    //ユーザー一覧表示
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');

    // 追加：ユーザー詳細表示
    Route::get('/users/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');

      // ユーザー勤怠（月次）
    Route::get('/users/{id}/attendance/monthly', [AdminAttendanceController::class, 'showMonthly'])->name('users.attendance.monthly');

    // ユーザー勤怠（日別）
    Route::get('/users/{id}/attendance/daily/{date}', [AdminAttendanceController::class, 'showDaily'])->name('users.attendance.daily');


    //出勤一覧
    Route::get('/attendance', [AdminAttendanceController::class, 'index'])->name('attendance.index');

    // 承認関連
 //このコードは次のような動作を定義しています：
//ユーザーが http://your-domain.com/attendance/requests にアクセスすると、このルートが処理されます。
// このリクエストは AdminAttendanceController クラスの requests メソッドに渡されます。
// このルートには 'admin.attendance.requests' という名前が付けられているため、アプリケーション内で簡単に参照できます。

    Route::get('/attendance/requests', [AdminAttendanceController::class, 'requests'])
    ->name('attendance.requests');



  Route::get('/attendance/working-hour-requests', [AdminAttendanceController::class, 'editWorkingHourRequests'])->name('attendance.working-hour-requests');
    Route::get('/attendance/requests/approved', [AdminAttendanceController::class, 'approvedRequests'])->name('attendance.requests.approved');
    Route::get('/attendance/request/{id}', [AdminAttendanceController::class, 'requestDetail'])->name('attendance.request.detail');

    Route::post('/attendance/approve/{id}', [AdminAttendanceController::class, 'approveRequest'])->name('attendance.approve');

//ユーザーを管理者に設定する
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    //ユーザーを管理者に昇格
    Route::post('/users/promote/{id}', [UserController::class, 'promoteToAdmin'])->name('users.promote');
    //ユーザーを管理者から降格
    Route::post('/users/demote/{id}', [UserController::class, 'demoteFromAdmin'])->name('users.demote');
});

//----------------------------------------------------------
//CSVエクスポート機能を追加時に書いた
Route::get('/admin/users/{user}/monthly-attendance/export', [AdminAttendanceController::class, 'exportMonthlyAttendance'])->name('admin.attendance.export');


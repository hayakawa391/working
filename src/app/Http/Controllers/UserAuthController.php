<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Models\User;

class UserAuthController extends Controller
{
    



    //トップページ作成時に追加。ログインのトップページを表示するためにindex.blade.php を呼び出す処理
    public function user_index()
    {
        $users = User::all(); //全ユーザー情報を取得
        return view('user.index', compact('users')); //user.indexビューに渡す


        // return view('user_index.blade.php');
    }

    //一般ユーザーのログインページ
    public function showLoginForm()
    {
    return view('user.auth.login'); // resources/views/user/auth/login.blade.php を表示
    }

    //ログインボタンが押された後、ログインを試みて、成功したとき個人用の勤怠ページへ繋げるためのメソッド
    public function login(Request $request){
         // バリデーション（入力値が正しい書式かをチェックする）
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // ログイン試行
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('attendance.index'); // ログイン成功後,/attendaceへリダイレクト
        }

        // 失敗時
        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが正しくありません。',
        ])->onlyInput('email');
    
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm(){
        // 管理者ログインページを表示
        return view('admin.login');
    }

    // 管理者ログイン処理
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user && $user->is_admin) {
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return redirect()->route('admin.login')->withErrors(['email' => '管理者専用ページです。']);
            }
        } else {
            return redirect()->route('admin.login')->withErrors(['email' => 'メールアドレスまたはパスワードが正しくありません。']);
        }
    }

    // 管理者ログアウト処理
     public function logout() {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
// //トップページ作成時に追加。ログインのトップページを表示するためにindex.blade.php を呼び出す処理
// public function index()
// {
//     return view('admin_index');
// }

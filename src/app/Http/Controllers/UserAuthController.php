<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    public function showLoginForm()
{
    return view('auth.login'); // resources/views/auth/login.blade.php を表示
}

}

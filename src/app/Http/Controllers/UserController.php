<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;

class UserController extends Controller
{
     // ユーザー一覧（管理者用）
    public function index()
    {
        $users = User::all(); //全ユーザー取得
        return view('admin.users.index', compact('users'));
    }

    public function show($id)
{
    $user = \App\Models\User::findOrFail($id); // 該当ユーザー取得（404も返す）
    $attendances = $user->attendances()->orderBy('created_at', 'desc')->get(); // 出退勤を取得
    //変数$userをビューに渡すためのもので、ビュー内でこの変数を使ってユーザー情報を表示する
    return view('admin.users.show', compact('user'));
}

    // 管理者に昇格
    public function promoteToAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->is_admin = true;
        $user->save();

        return redirect()->back()->with('success', 'ユーザーを管理者に昇格しました。');
    }

    // 管理者から降格
    public function demoteFromAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->is_admin = false;
        $user->save();

        return redirect()->back()->with('success', 'ユーザーを一般ユーザーに降格しました。');
    }
}


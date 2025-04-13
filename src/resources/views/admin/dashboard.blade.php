@extends('admin.layouts.app')

@section('content')
    <h2>管理者専用ページ</h2>
    <p>ようこそ、管理者ページへ！</p>
    
    <a href="{{ route('admin.users.index') }}">
    <button>ユーザー一覧を見る</button>
    </a>
    
    <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">出退勤一覧を見る</a>

    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit">ログアウト</button>
    </form>
@endsection

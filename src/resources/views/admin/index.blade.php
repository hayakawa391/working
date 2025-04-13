<!-- ユーザーを管理者設定する -->

@extends('user.layouts.app')

@section('content')
<div class="container">
    <h2>ユーザー一覧</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>名前</th>
                <th>メール</th>
                <th>権限</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->is_admin ? '管理者' : '一般ユーザー' }}</td>
                <td>
                    @if ($user->is_admin)
                        <form method="POST" action="{{ route('admin.users.demote', $user->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-warning btn-sm">降格</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.users.promote', $user->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">管理者にする</button>
                        </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection

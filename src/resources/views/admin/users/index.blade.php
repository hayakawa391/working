<!-- 管理者側から全ユーザーの氏名・メアド・詳細を表で確認する場所 -->
 @extends('admin.layouts.app') {{-- レイアウト使ってる場合 --}}
@section('content')
    <h1>ユーザー一覧</h1>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>氏名</th>
                <th>メールアドレス</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                     <td>
                        <a href="{{ route('admin.users.show', $user->id) }}">詳細</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection


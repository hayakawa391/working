<!-- 管理者ページからの「詳細」で各ユーザーの出退勤・休憩の一覧表示 -->
 @extends('admin.layouts.app')

@section('content')
    <h1>{{ $user->name }} さんの詳細</h1>

    <p><strong>メールアドレス：</strong> {{ $user->email }}</p>
    <p><strong>管理者：</strong> {{ $user->is_admin ? 'はい' : 'いいえ' }}</p>

    <h2>出退勤・休憩履歴</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩開始</th>
                <th>休憩終了</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->created_at->format('Y-m-d') }}</td>
                    <td>{{ $attendance->clock_in }}</td>
                    <td>{{ $attendance->clock_out }}</td>
                    <td>{{ $attendance->break_start }}</td>
                    <td>{{ $attendance->break_end }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">記録がありません。</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br>
    <a href="{{ route('admin.users.index') }}">← ユーザー一覧に戻る</a>
@endsection

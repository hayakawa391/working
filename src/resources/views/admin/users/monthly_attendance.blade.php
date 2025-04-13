<!-- 管理者ページからの「詳細」で各ユーザーの月次勤怠表示 -->
@extends('admin.layouts.app')

@section('content')
    <h2>{{ $user->name }} さんの勤怠（月次）</h2>

    @php
        $prevMonth = \Carbon\Carbon::parse($month)->subMonth()->format('Y-m');
        $nextMonth = \Carbon\Carbon::parse($month)->addMonth()->format('Y-m');
    @endphp

    <div>
        <a href="{{ route('admin.users.attendance.monthly', ['id' => $user->id, 'month' => $prevMonth]) }}">← 前月</a>
        <strong>{{ \Carbon\Carbon::parse($month)->format('Y年m月') }}</strong>
        <a href="{{ route('admin.users.attendance.monthly', ['id' => $user->id, 'month' => $nextMonth]) }}">翌月 →</a>

<!-- 「CSVエクスポート」ボタンを追加する -->
        <a href="{{ route('admin.attendance.export', ['user' => $user->id, 'month' => $currentMonth]) }}" class="btn btn-primary">
    CSVエクスポート</a>
    </div>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩開始</th>
                <th>休憩終了</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->clock_in }}</td>
                    <td>{{ $attendance->clock_out }}</td>
                    <td>{{ $attendance->break_start }}</td>
                    <td>{{ $attendance->break_end }}</td>
                    <td>
                        <a href="{{ route('admin.users.attendance.daily', ['id' => $user->id, 'date' => $attendance->date]) }}">
                            詳細
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

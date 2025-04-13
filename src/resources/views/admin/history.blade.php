<!-- 全ユーザー出勤一覧表示 -->

@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>一般ユーザーの出退勤一覧</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ユーザー名</th>
                <th>日付</th>
                <th>出勤時間</th>
                <th>退勤時間</th>
                <th>休憩時間</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->name }}</td>
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->clock_in }}</td>
                    <td>{{ $attendance->clock_out ?? '未退勤' }}</td>
                    <td>
                        @foreach ($attendance->breakTimes as $break)
                            {{ $break->break_start }} - {{ $break->break_end ?? '未終了' }}<br>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">管理者ダッシュボードに戻る</a>
</div>
@endsection

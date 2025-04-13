<!-- 各日付の詳細ページの表示 -->
 @extends('user.layouts.app')

@section('content')
<div class="container">
    <h2>{{ $attendance->date }}の詳細</h2>

    <ul class="list-group mb-3">
        <li class="list-group-item">出勤時間: {{ $attendance->clock_in }}</li>
        <li class="list-group-item">退勤時間: {{ $attendance->clock_out ?? '未退勤' }}</li>
        <li class="list-group-item">
            <strong>休憩時間:</strong><br>
            @foreach($attendance->breakTimes as $break)
                {{ $break->break_start }} - {{ $break->break_end ?? '未終了' }}<br>
            @endforeach
        </li>
        <li class="list-group-item">備考:  {{ $attendance->note ?? '（なし）' }}</li>
        <a href="{{ route('attendance.note.edit', $attendance->id) }}" class="btn btn-warning">備考を編集</a>

    </ul>

    <a href="{{ route('admin.attendance.monthly') }}" class="btn btn-secondary">月次一覧へ戻る</a>
</div>
@endsection

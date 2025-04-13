<!-- 月次勤怠一覧 -->
 @extends('user.layouts.app')

@section('content')
<div class="container">
    <h2>{{ \Carbon\Carbon::parse($currentMonth)->format('Y年m月') }}の勤怠一覧</h2>

    <table class="table">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>労働時間</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
                @php
                    $breakTotal = $attendance->breakTimes->sum(function($break) {
                        if ($break->break_start && $break->break_end) {
                            return \Carbon\Carbon::parse($break->break_end)->diffInMinutes(\Carbon\Carbon::parse($break->break_start));
                        }
                        return 0;
                    });
                    $workTime = 0;
                    if ($attendance->clock_in && $attendance->clock_out) {
                        $workTime = \Carbon\Carbon::parse($attendance->clock_out)->diffInMinutes(\Carbon\Carbon::parse($attendance->clock_in)) - $breakTotal;
                    }
                @endphp
                <tr>
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->clock_in }}</td>
                    <td>{{ $attendance->clock_out ?? '未退勤' }}</td>
                    <td>{{ floor($breakTotal / 60) }}時間 {{ $breakTotal % 60 }}分</td>
                    <td>{{ floor($workTime / 60) }}時間 {{ $workTime % 60 }}分</td>
                    <td>
                        <a href="{{ route('attendance.detail', $attendance->id) }}" class="btn btn-info btn-sm">詳細</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

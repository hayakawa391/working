@extends('user.layouts.app')

@section('content')
<div class="container">
    <h2>出勤履歴</h2>

    <table class="table">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤時間</th>
                <th>退勤時間</th>
                <th>休憩時間</th>
                <th>変更</th>
            </tr>
        </thead>
        <tbody>
             @foreach ($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->date }}</td>
                    <td>{{ $attendance->clock_in }}</td>
                    <td>{{ $attendance->clock_out ?? '未退勤' }}</td>
                    <td>
                        @foreach ($attendance->breakTimes as $break)
                            {{ $break->break_start }} - {{ $break->break_end ?? '未終了' }}<br>
                        @endforeach
                    </td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal{{ $attendance->id }}">変更申請</button>
                    </td>
                </tr>

                <!-- 変更申請モーダル -->
                <div class="modal fade" id="editModal{{ $attendance->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form action="{{ route('attendance.request-edit', $attendance->id) }}" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">出勤記録の変更申請</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <label>新しい出勤時間:</label>
                                    <input type="time" name="new_clock_in" class="form-control" value="{{ $attendance->clock_in }}">

                                    <label>新しい退勤時間:</label>
                                    <input type="time" name="new_clock_out" class="form-control" value="{{ $attendance->clock_out }}">

                                    <label>変更理由:</label>
                                    <textarea name="reason" class="form-control" required></textarea>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">申請</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
            
        </tbody>
    </table>
    <a href="{{ route('attendance.index') }}" class="btn btn-primary">戻る</a>
    
</div>
@endsection
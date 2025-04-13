<!-- ログイン後、のページ　
 その日の出退勤・休憩をクリックする画面 -->
@extends('user.layouts.app')

@section('content')
<div class="container">
    <h2>出退勤管理</h2>

    <!-- 現在の日時を表示 -->
    <p>現在日時: <span id="current-time">{{ now()->format('Y-m-d H:i:s') }}</span></p>

    <!-- 出勤一覧を見るボタン -->
    <a href="{{ route('admin.attendance.monthly') }}" class="btn btn-secondary">出勤一覧</a>

    @if(!$attendance) 
        <!-- まだ出勤していない場合 -->
        <form action="{{ route('attendance.clock-in') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">出勤</button>
        </form>

    @elseif(!$attendance->clock_out)
        @php
            // 未終了の休憩（＝休憩中）を取得
            $onBreak = $attendance->breakTimes->whereNull('break_end')->isNotEmpty();
        @endphp

        @if($onBreak)
            <!-- 休憩中の場合 -->
            <form action="{{ route('attendance.break-end') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-success">休憩戻</button>
            </form>
        @else
            <!-- 出勤中かつ休憩していない場合 -->
            <form action="{{ route('attendance.break-start') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-warning">休憩入</button>
            </form>
        @endif

        <!-- 退勤ボタン -->
        <form action="{{ route('attendance.clock-out') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-danger">退勤</button>
        </form>

    @else
        <!-- 退勤済みの場合 -->
        <p>お疲れ様でした。</p>
    @endif
</div>

<!-- JavaScriptで現在時刻をリアルタイム更新 -->
<script>
    function updateTime() {
        document.getElementById('current-time').textContent = new Date().toLocaleString();
    }
    setInterval(updateTime, 1000);
</script>

@endsection

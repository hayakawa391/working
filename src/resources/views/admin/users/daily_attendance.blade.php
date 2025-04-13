<!--各ユーザーの月次勤怠表示 からの「詳細」で日別勤怠表示 -->
@extends('admin.layouts.app')

@section('content')
    <h2>{{ $user->name }} さんの {{ $attendance->date }} の勤怠詳細</h2>

    <ul>
        <li>出勤時刻: {{ $attendance->clock_in }}</li>
        <li>退勤時刻: {{ $attendance->clock_out }}</li>
        <li>休憩開始: {{ $attendance->break_start }}</li>
        <li>休憩終了: {{ $attendance->break_end }}</li>
    </ul>

    <a href="{{ url()->previous() }}">← 月次画面に戻る</a>
@endsection

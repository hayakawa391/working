<!-- 申請の内容表示 -->
 @extends('admin.layouts.app')

@section('content')
<h2>申請詳細</h2>

<p>ユーザー名: {{ $request->user->name }}</p>
<p>日付: {{ $request->attendance->date }}</p>
<p>修正内容: {{ $request->target_column }}</p>
<p>理由: {{ $request->reason }}</p>

<p>現在の値: {{ $request->old_value }}</p>
<p>申請後の値: {{ $request->new_value }}</p>

<form method="POST" action="{{ route('admin.attendance.approve', $request->id) }}">
    @csrf
    <button type="submit">承認する</button>
</form>
@endsection

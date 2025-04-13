<!-- 申請一覧の表示 -->
 @extends('admin.layouts.app')

@section('content')
<h2>承認待ち申請一覧</h2>

<table>
    <thead>
        <tr>
            <th>ユーザー名</th>
            <th>日付</th>
            <th>修正対象</th>
            <th>申請理由</th>
            <th>詳細</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pendingRequests as $request)
        <tr>
            <td>{{ $request->user->name }}</td>
            <td>{{ $request->attendance->date }}</td>
            <td>{{ $request->target_column }}</td>
            <td>{{ $request->reason }}</td>
            <td><a href="{{ route('admin.attendance.request.detail', $request->id) }}">詳細</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection

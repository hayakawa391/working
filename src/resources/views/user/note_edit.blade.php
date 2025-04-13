<!-- 出退勤の詳細ページ　備考欄の編集ページ -->
 @extends('user.layouts.app')

@section('content')
<div class="container">
    <h2>備考編集</h2>

    <form action="{{ route('attendance.note.update', $attendance->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="note">備考内容:</label>
            <textarea name="note" id="note" class="form-control" rows="4">{{ old('note', $attendance->note) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-2">保存</button>
        <a href="{{ route('attendance.detail', $attendance->id) }}" class="btn btn-secondary mt-2">戻る</a>
    </form>
</div>
@endsection

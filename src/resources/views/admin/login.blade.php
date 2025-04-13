@extends('user.layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection


@section('content')
    <h2>管理者ログイン</h2>

    <form method="POST" action="{{ route('admin.login.submit') }}"> <!-- submitは「このルートはPOSTに使われる」という意味 -->
        @csrf
        <div>
            <label for="email">メールアドレス:</label>
            <input type="email" name="email" required>
        </div>

        <div>
            <label for="password">パスワード:</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit">ログイン</button>
    </form>

    @if ($errors->any())
        <div>
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
@endsection

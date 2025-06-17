<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>COACHTECH</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
  <link rel="stylesheet" href="{{ asset('css/common.css') }}">
  @yield('css')
  <style>
    .header {
      background-color: #000;
      color: white;
      padding: 1rem 2rem;
    }

    .header__inner {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header__logo {
      color: white;
      font-size: 1.5rem;
      font-weight: bold;
      text-decoration: none;
    }

    .header-nav {
      display: flex;
      gap: 1.5rem;
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .header-nav__item a,
    .header-nav__item button {
      color: white;
      text-decoration: none;
      background: none;
      border: none;
      cursor: pointer;
      font-size: 1rem;
      font-weight: bold;
    }

    .header-nav__item button:hover,
    .header-nav__item a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body>
  <header class="header">
    <div class="header__inner">
      <a class="header__logo" href="/">COACHTECH</a>

      @auth
      <nav>
        <ul class="header-nav">
          <li class="header-nav__item">
            <a href="{{ route('admin.attendance.monthly') }}">勤怠一覧</a>
          </li>
          <li class="header-nav__item">
            <a href="{{ route('admin.users.index') }}">スタッフ一覧</a>
          </li>
          <li class="header-nav__item">
            <a href="{{ route('admin.attendance.requests') }}">申請一覧</a>
          </li>
          <li class="header-nav__item">
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit">ログアウト</button>
            </form>
          </li>
        </ul>
      </nav>
      @endauth
    </div>
  </header>

  <main>
    @yield('content')
  </main>
</body>

</html>

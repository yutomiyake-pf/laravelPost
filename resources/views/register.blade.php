<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <title>ユーザー登録画面</title>
</head>
<body>
    <header>
        <h1>Laravelの練習</h1>
    </header>

    <div class="title-wrapper">
        <h1>ユーザー登録ページ</h1>
    </div>

    <div class="menu-wrapper">
        <a href="{{ route('login') }}">Loginはこちら</a>
    </div>

    @if (session('message'))
        <div class="flash_message">
            {{ session('message') }}
        </div>
    @endif


    <div class="form-wrapper">

        <form action="{{ route('register') }}" method="post">
        @csrf
            名前
            <p style="color: red;">
                @if ($errors->has('name'))
                {{ $errors->first('name') }} 
                @endif
            </p>
            <input type="text" name="name" class="register-form" placeholder="Name" value="{{ old('name') }}">
            メールアドレス
            <p style="color: red;">
                @if ($errors->has('email'))
                {{ $errors->first('email') }}
                @endif
            </p>
            <input type="email" name="email" class="register-form" placeholder="Email" value="{{ old('email') }}">
            パスワード
            <p style="color: red;">
                @if ($errors->has('password'))
                {{ $errors->first('password') }}
                @endif
            </p>
            <input type="password" name="password" class="register-form" placeholder="Password" value="{{ old('password') }}">
            <input type="submit" value="登録" class="register-form register-submit">
        </form>
    </div>
</body>
</html>
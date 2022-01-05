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
        <h1>ログイン</h1>
    </div>

    <div class="menu-wrapper">
        <a href="{{ route('registerView') }}">会員登録はこちら</a>
    </div>

    @if (session('message'))
        <div class="flash_message">
            {{ session('message') }}
        </div>
    @endif


    <div class="form-wrapper">

        <form action="{{ route('login') }}" method="post">
            @csrf
            メールアドレス
            <input type="email" name="email" class="register-form" placeholder="Email" value="{{ isset($email) ? $email : "" }}">
            パスワード
            <input type="password" name="password" class="register-form" placeholder="Password" value="{{ isset($password) ? $password : "" }}">
            <input type="submit" value="ログイン" class="register-form register-submit">
        </form>
    </div>
</body>
</html>
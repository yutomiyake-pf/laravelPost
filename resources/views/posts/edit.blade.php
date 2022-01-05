<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/posts/edit.css') }}">
    <title>編集画面</title>
</head>
<body>
    <header>
        <h1>Laravelの練習</h1>
    </header>

    <div class="title-wrapper">
        <h1>投稿編集</h1>
    </div>

    <div class="menu-wrapper">
        <a href="{{ route('postView') }}">投稿一覧へ</a>
    </div>

    @if (session('message'))
        <div class="flash_message">
            {{ session('message') }}
        </div>
    @endif

    <div id="main-wrap">
        <div id="form-wrap">
            <form method="post">
                @csrf
                <p style="color: red;">
                    @if ($errors->has('message'))
                    {{ $errors->first('message') }} 
                    @endif
                </p>
                <textarea name="message" id="" cols="30" rows="10" placeholder="投稿は200文字以内です。" class="edit-erea">{{ isset($postData->message) ? $postData->message : ""}}</textarea>

                <input type="submit" value="編集" class="post-submit">
            </form>
        </div>
    </div>
</body>
</html>
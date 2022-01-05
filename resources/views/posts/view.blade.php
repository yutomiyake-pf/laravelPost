<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/posts/postmodal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/posts/view.css') }}">
    <title>投稿画面</title>
</head>
<body>
    <header>
        <h1>Laravelの練習</h1>
    </header>

    <div class="title-wrapper">
        <h1>投稿一覧</h1>
    </div>

    <div class="menu-wrapper">
        <a href="{{ route('logout') }}" onclick="return confirm('ログアウトしますか？')">ログアウト</a>
    </div>

    @if (session('message'))
        <div class="flash_message">
            {{ session('message') }}
        </div>
    @endif

    @if ($errors->has('message'))
        <div class="flash_message">
            投稿に不備があります。ご確認ください。
        </div>
    @endif

    {{-- 投稿モーダル --}}
    <div id="open">
        投稿する
    </div>
    <div id="mask" class="hidden"></div>
    <section id="modal" class="hidden">
        <form action="" method="post" class="post-form">
            @csrf
            <p style="color: red;">
                @if ($errors->has('message'))
                {{ $errors->first('message') }} 
                @endif
            </p>
            <textarea name="message" id="" cols="30" rows="10" class="post-area" placeholder="投稿は200文字以内です。"></textarea>
            <input type="submit" value="投稿" class="post-submit">
        </form>
    <div id="close">
        閉じる
    </div>
    </section>
    {{-- 投稿モーダルここまで --}}

    <div class="post-list-wrapper">
        @if ($posts->isEmpty())
            投稿がありません
            
        @else
            @foreach ($posts as $post)
                <div class="message-list-wrapper">
                    <div class="message">
                        <p>{{ !isset($post->message) ? 'message読み込み失敗' : $post->message }}</p>
                    </div>

                    <div class="message-info">
                        <p>投稿者:　{{ empty($post->user->user_name) ? '投稿者の読み込み失敗' : $post->user->user_name}}</p>
                        <p>投稿日:　{{ $post->created_at }}</p>
                    </div>

                    <div>
                        @if ($userId == $post->user_id)
                        <form action="deletePost" name="deleteForm" method="POST">
                            @csrf
                            <input type="hidden" value="{{ $post->id }}" name="post_id">
                        </form>
                        <a href="javascript:document.forms.deleteForm.submit()" onclick="return confirm('本当に削除しますか？');">削除</a>
                        <a href="{{ route('editPost', $post->id) }}">編集</a>
                        @endif
                        <a href="{{ route('reply', $post->id) }}">返信スレッド</a>
                    </div>
                </div>
                <hr>
            @endforeach
        @endif
    </div>

    @if (!$posts->isEmpty())
    <div class="paginate-wrap">
        {{ $posts->links() }}
    </div>
    @endif



<script src="{{ asset('js/postmodal.js') }}"></script>
</body>
</html>
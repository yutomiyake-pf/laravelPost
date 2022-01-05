<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/posts/postmodal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/posts/view.css') }}">
    <link rel="stylesheet" href="{{ asset('css/posts/reply.css') }}">
    <title>返信画面</title>
</head>
<body>
    <header>
        <h1>Laravelの練習</h1>
    </header>

    <div class="title-wrapper">
        <h1>返信一覧</h1>
    </div>

    <div class="menu-wrapper">
        <a href="{{ route('postView') }}">投稿一覧へ</a>
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

    <div id="reply-post">
        <div class="reply-message-wrap">
            <p class="reply-message">
                {{ $replyPost->message }}
            </p>

            <p>投稿者:　{{ empty($replyPost->user->user_name) ? '投稿者の読み込み失敗' : $replyPost->user->user_name }}</p>

            <p>投稿日:　{{ $replyPost->created_at }}</p>

            <p>への返信スレッドです。</p>
        </div>
    </div>

    {{-- 投稿モーダル --}}
    <div id="open">
        返信する
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
            <textarea name="message" id="" cols="30" rows="10" class="post-area" placeholder="投稿は200文字以内です。">{{ old('message') }}</textarea>
            <input type="submit" value="返信" class="post-submit">
        </form>
    <div id="close">
        閉じる
    </div>
    </section>
    {{-- 投稿モーダルここまで --}}

    <div class="post-list-wrapper">
        @if ($replies->isEmpty())
            返信がありません
        @else
            @foreach ($replies as $rep)
                <div class="message-list-wrapper">
                    <div class="message">
                        <p>{{ !isset($rep->message) ? 'message読み込み失敗' : $rep->message }}</p>
                    </div>

                    <div class="message-info">
                        <p>投稿者:　{{ empty($rep->user->user_name) ? '投稿者の読み込み失敗' : $rep->user->user_name}}</p>
                        <p>投稿日:　{{ $rep->created_at }}</p>
                    </div>

                    <div>
                        @if ($userId == $rep->user_id)
                        <form action="{{ route('deletePost') }}" name="deleteForm" method="POST">
                            @csrf
                            <input type="hidden" value="{{ $rep->id }}" name="post_id">
                        </form>
                        <a href="javascript:document.forms.deleteForm.submit()" onclick="return confirm('本当に削除しますか？');">削除</a>
                        <a href="{{ route('editPost', $rep->id) }}">編集</a>
                        @endif
                    </div>
                </div>
                <hr>
            @endforeach
        @endif
    </div>

    @if (!$replies->isEmpty())
    <div class="paginate-wrap">
        {{ $replies->links() }}
    </div>
    @endif



<script src="{{ asset('js/postmodal.js') }}"></script>
</body>
</html>
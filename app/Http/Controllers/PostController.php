<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\User;
use App\Post;

class PostController extends Controller
{

    /**
     * 無理やりログインチェック
     *
     * @return bool
     */
    private function chkLogin() {
        $data = session()->all();

        if (!isset($data['id']) || !isset($data['user_name']) || !isset($data['email'])) {
            session()->flash('message', 'ログインしてください。');
            return false;
        }

        return true;
    }
    

    /**
     * 投稿一覧＆投稿処理
     *
     */
    public function postView(Request $request) {
        if (!$this->chkLogin()) return redirect()->route('login');//ログインチェック
        $userId = session('id');
        $userName = session('user_name');
        $post = new Post;

        if ($request->isMethod('get')) {
            $posts = $post->getAllNotReply();
            return view('posts.view',compact('posts','userId'));
        }

        //投稿処理
        if ($request->isMethod('post')) {
            $validate = $this->getPostValidate();
            $this->validate($request, $validate['rule'], $validate['message']);

            $insertData = [
                'user_id' => $userId,
                'message' => $request->message,
                'reply_post_id' => 0,
                'created_at' => now(),
                'modified' => now()
            ];
            
            try {
                if (!$post->insert($insertData)) throw new Exception('投稿に失敗しました。 : サーバーエラー');
            } catch (Exception $e) {
                session()->flash('message', $e->getMessage());
                return redirect(route('postView'));
            }

            session()->flash('message', '投稿が完了しました。');
            return redirect(route('postView'));
        }
    }

    /**
     * 投稿用バリデーション
     *
     * @return array
     */
    private function getPostValidate() {

        $rule = [
            'message'     => 'required | max:200',
        ];

        $messages = [
            'message.required'     => '投稿は1文字以上でお願いします。',
            'message.max'          => '投稿は200文字以内で入力してください。',
        ];

        $validate = [
            'rule' => $rule,
            'message' => $messages
        ];

        return $validate;
    }

    /**
     * 投稿削除
     *
     * @param Request $request
     * @return bool
     */
    public function deletePost(Request $request) {
        if (!$this->chkLogin()) return redirect()->route('login');//ログインチェック

        if (!$requestPostId = $request->post_id) {
            session()->flash('message', '不正なデータが送られてきました。');
            return redirect(route('postView'));
        }

        $post = new Post;
        if (!$postUserId = $post->getUserIdByPostId($requestPostId)) {
            session()->flash('message', '不正なデータが送られてきました。');
            return redirect(route('postView'));
        }

        if (session('id') !== $postUserId->user_id) {
            session()->flash('message', '不正なアクセスです。');
            return redirect(route('postView'));
        }

        try {
            $post->deletePost($requestPostId);
        } catch(Exception $e) {
            session()->flash('message', $e->getMessage());
            return redirect(route('postView'));
        }

        session()->flash('message', '投稿を削除しました。');
        return redirect(route('postView'));
    }

    /**
     * 投稿編集
     *
     * @param Request $request
     * @param [int] $postId
     */
    public function editPost(Request $request, $postId) {
        if (!$this->chkLogin()) return redirect()->route('login');//ログインチェック
        $post = new Post;
        $userId = session('id');

        if (!$postData = $post->getByPostId($postId)) {
            session()->flash('message', '投稿データを正常に読み込めませんでした。');
            return redirect(route('postView'));
        }
        
        if ($postData->user_id !== $userId) {
            session()->flash('message', '不正なアクセスです。');
            return redirect(route('postView'));
        }

        if ($request->isMethod('get')) {
            return view('posts.edit',compact('postData'));
        }

        //編集処理
        if ($request->isMethod('post')) {
            $validate = $this->getPostValidate();
            $this->validate($request, $validate['rule'], $validate['message']);

            $editData = [
                'id' => $postId,
                'message' => $request->message
            ];

            try {
                $post->edit($editData);
            } catch(Exception $e) {
                session()->flash('message', $e->getMessage());
                return redirect(route('postView'));
            }

            session()->flash('message', '編集が完了しました。');
            return redirect(route('postView'));
        }
    }

    public function reply(Request $request, $postId) {
        if (!$this->chkLogin()) return redirect()->route('login');//ログインチェック
        $post = new Post;
        $userId = session('id');

        if (!$replyPost = $post->getNotReplyByPostId($postId)) {
            session()->flash('message', '投稿データを取得できませんでした。');
            return redirect(route('postView'));
        }

        if ($request->isMethod('get')) {

            $replies = $post->getReplyByReplyPostId($postId);
            return view('posts.reply',compact('replyPost', 'replies', 'userId'));
        }

        //返信処理
        if ($request->isMethod('post')) {
            $validate = $this->getPostValidate();
            $this->validate($request, $validate['rule'], $validate['message']);

            $insertData = [
                'user_id' => $userId,
                'message' => $request->message,
                'reply_post_id' => $postId,
                'created_at' => now(),
                'modified' => now()
            ];
            
            try {
                if (!$post->insert($insertData)) throw new Exception('返信に失敗しました。 : サーバーエラー');
            } catch (Exception $e) {
                session()->flash('message', $e->getMessage());
                return redirect(route('reply', $postId));
            }

            session()->flash('message', '返信が完了しました。');
            return redirect(route('reply', $postId));
        }

    }
}

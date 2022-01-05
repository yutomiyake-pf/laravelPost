<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class Post extends Model
{
    protected $table = 'posts';
    public $timestamps = false;

    //リレーション
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * 投稿
     *
     * @param [array] $data
     */
    public function insert($data) {
        if (!$data) return false;

        $result = DB::table($this->table)->insert([
            'user_id' => $data['user_id'],
            'message' => $data['message'],
            'reply_post_id' => $data['reply_post_id'],
            'created_at' => $data['created_at'],
            'modified' => $data['modified']
        ]);

        return $result;
    }

    /**
     * 投稿一覧取得
     *
     */
    public function getAllNotReply() {
        $result = Post::with('user:id,user_name,email')
        ->where('reply_post_id', 0)
        ->where('delete_flag', 0)
        ->orderBy('created_at', 'desc')
        ->paginate(5);

        return $result;
    }

    /**
     * postIdからuser_idのみを取得
     *
     * @param [int] $postId
     */
    public function getUserIdByPostId($postId) {
        if (!$postId) return false;

        $result = Post::where('id', $postId)
        ->select('user_id')
        ->first();

        return $result;
    }

    /**
     * postidから削除
     *
     * @param [int] $postId
     * @return int
     */
    public function deletePost($postId) {
        if (!$postId) throw new Exception('不正なデータが送られてきました。');

        $result = Post::where('id', $postId)
        ->update([
            'delete_flag' => 1,
            'modified' => now()
        ]);

        if (!$result) throw new Exception('削除に失敗しました。');

        $result;
    }

    /**
     * postIdから取得
     *
     * @param [int] $postId
     */
    public function getByPostId($postId) {
        if (!$postId) return false;

        $result = Post::where('id', $postId)
        ->where('delete_flag', 0)
        ->first();

        return $result;
    }

    /**
     * 投稿編集
     *
     * @param [array] $editData
     * @return int
     */
    public function edit($editData) {
        if (!$editData['id'] || !isset($editData['message'])) throw new Exception('不正なデータが送られてきました。');

        $result = Post::where('id', $editData['id'])
        ->update([
            'message' => $editData['message'],
            'modified' => now()
        ]);

        if (!$result) throw new Exception('編集が正常に行われませんでした。');

        return $result;
    }

    public function getNotReplyByPostId ($postId) {
        if (!$postId) return false;

        
        $result = Post::with('user:id,user_name,email')
        ->where('reply_post_id', 0)
        ->where('delete_flag', 0)
        ->where('id', $postId)
        ->first();

        return $result;
    }

    public function getReplyByReplyPostId($replyPostId) {
        if (!$replyPostId) return false;

        $result = Post::with('user:id,user_name,email')
        ->where('reply_post_id', $replyPostId)
        ->where('delete_flag', 0)
        ->orderBy('created_at', 'desc')
        ->paginate(5);

        return $result;
    }
}

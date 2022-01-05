<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    protected $table = 'users';

    /**
     * ユーザー登録
     *
     * @param [array] $data
     * @return boolean
     */
    public function insertUser($data) {
        if (!$data) return false;

        $result = DB::table($this->table)->insertGetId([
                    'user_name'  => $data['user_name'],
                    'email'      => $data['email'],
                    'password'   => $data['password'],
                    'created_at' => now(),
                    'modified'   => now()
                ]);

        return $result;
    }

    //登録されているユーザーか確認
    public function chkUniqUser($email) {
        if (!$email) return true;//存在とする

        $result = DB::table($this->table)->where('email', $email)->exists();

        return $result;
    }

    /**
     * emailとpasswordからユーザー取得
     *ログインで使う
     * 
     * @param [text] $email
     * @param [hash] $password
     * @return bool
     */
    public function getByEmailAndPassForLogin($email, $password) {
        if (!$email || !$password) return false;

        $return = DB::table($this->table)
                ->where('email', $email)
                ->where('password', $password)
                ->first();
        
        if (!$return) return false;

        return $return;
    }
}

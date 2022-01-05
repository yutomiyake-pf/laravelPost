<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Validator;
use App\User;


class UserController extends Controller
{
    public function registerView() {
        return view('register');
    }

    /**
     * 会員登録処理
     */
    public function register(Request $request) {
        $validate = $this->getRegiValidate();
        $this->validate($request, $validate['rule'], $validate['message']);


        $name = $request->name;
        $email = $request->email;
        $password = $request->password;

        $insertData = [
            'user_name' => $name,
            'email'     => $email,
            'password'   => md5($password)
        ];

        $user = new User();

        //初登録かの確認
        if($user->chkUniqUser($email)) {
            session()->flash('message', 'すでに登録されています。');
            return redirect(route('registerView'));
        }

        //登録処理
        try {
            if (!$id = $user->insertUser($insertData)) {
                throw new Exception('ユーザー登録に失敗しました。 : サーバーエラー');
            }
        } catch(Exception $e) {
            session()->flash('message', $e->getMessage());
            return redirect(route('registerView'));
        }

        session()->put([
            'id' => $id,
            'user_name' => $name,
            'email' => $email
        ]);

        return redirect()->route('postView');
    }

    /**
     * ユーザー登録用バリデーション
     *
     * @return array
     */
    private function getRegiValidate() {

        $rule = [
            'name'     => 'required | max:20',
            'email'    => 'email | max:50',
            'password' => 'required | regex:/^[a-zA-Z0-9]+$/ | min:8'
        ];

        $messages = [
            'name.required'     => '名前は必ず入力してください。',
            'name.max'          => '名前は20文字以内で入力してください',
            'email.email'       => '正しいE-mailアドレスを入力してください',
            'email.max'         => 'E-mailは50文字以内で入力してください',
            'password.required' => 'パスワードを入力してください',
            'password.regex'    => 'パスワードに使えるのは半角英数字のみです' ,
            'password.min'      => 'パスワードは8文字以上で入力してください'
        ];

        $validate = [
            'rule' => $rule,
            'message' => $messages
        ];

        return $validate;
    }


    /**
     * ログイン
     *
     */
    public function login(Request $request) {

        if ($request->isMethod(('get'))) {
            return view('login');
        }

        if ($request->isMethod('post')) {
            $email = $request->email;
            $password = $request->password;
            if (!$email || !$password) {
                session()->flash('message', '正しいメールアドレスとパスワードを入力してください。');
                return redirect(route('login'));
            }

            $user = new User();

            if (!$login = $user->getByEmailAndPassForLogin($email, md5($password))) {
                session()->flash('message', '登録ユーザーがいません。');
                return view('login', compact('email', 'password'));
            }

            session()->put([
                'id' => $login->id,
                'user_name' => $login->user_name,
                'email' => $login->email
            ]);

            return redirect()->route('postView');
        }
    }

    public function logout() {
        session()->flush();

        session()->flash('message', 'logoutしました。');
        return redirect()->route('login');
    }
}

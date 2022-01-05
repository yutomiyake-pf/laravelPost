<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('registerView', 'UserController@registerView')->name('registerView');//会員登録画面

Route::post('register', 'UserController@register')->name('register');//会員登録処理

Route::match(['get', 'post'], 'login', 'UserController@login')->name('login');//ログイン画面＆処理

Route::get('logout', 'UserController@logout')->name('logout');

Route::match(['get', 'post'], 'postView', 'PostController@postView')->name('postView');//投稿一覧＆処理

Route::post('deletePost', 'PostController@deletePost')->name('deletePost');//投稿削除

Route::match(['get', 'post'], 'editPost/{postId}', 'PostController@editPost')->name('editPost');//投稿編集

Route::match(['get', 'post'], 'reply/{postId}', 'PostController@reply')->name('reply');//返信

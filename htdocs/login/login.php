<?php
/*
*  ログイン処理
*/
require_once '../../include/conf/const.php';
require_once '../../include/model/common/common_func.php';
require_once '../../include/model/login/login_func.php';
require_once '../../include/model/management/user_func.php';
// リクエストメソッド確認
if (get_request_method() !== 'POST') {
   // POSTでなければログインページへリダイレクト
   header('Location: ./login_top.php');
   exit;
}

// セッション開始
session_start();
// POST値取得
$user_name = get_post_data('user_name');  // メールアドレス
$password = get_post_data('password'); // パスワード
// ユーザー名をCookieへ保存
setcookie('user_name', $user_name, time() + 60 * 60 * 24 * 365);

// データベース接続
$link = get_db_connect();
//ユーザーネームとパスワードからuser_idを取得するSQL
$user_id = get_login_user($link, $user_name, $password);
// データベース切断
close_db_connect($link);

// 登録データを取得できたか確認
if (isset($user_id) === TRUE) {
   // セッション変数にuser_idを保存
   $_SESSION['user_id'] = $user_id;

   // セッション変数からuser_id取得
   $user_id = check_session_user_id($_SESSION['user_id'], './login_top.php');

   // データベース接続
   $link = get_db_connect();
   // ユーザ名を取得できたか確認
   $user_name =  check_user_name($link, $user_id, '../logout.php');
   // データベース切断
   close_db_connect($link);

   // ログイン済みの場合、ホームページへリダイレクト
   //shopユーザーを拒否(shopページに移動)
   rejection_shop_user($user_name, '../shop/insert_cart.php');
   //adminユーザーを拒否(adminページに移動)
   rejection_admin_user($user_name, '../management/item_management.php');

} else {
   // セッション変数にログインのエラーフラグを保存
   $_SESSION['login_err_flag'] = TRUE;
   // ログインページへリダイレクト
   header('Location: ./login_top.php');
   exit;
}

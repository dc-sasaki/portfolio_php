<?php
/*
*  ログインページ
*/
require_once '../../include/conf/const.php';
require_once '../../include/model/common/common_func.php';
require_once '../../include/model/login/login_func.php';
require_once '../../include/model/management/user_func.php';

// セッション開始
session_start();

//ログイン済みの時
if(isset($_SESSION['user_id']) === TRUE){
// セッション変数からuser_id取得
$user_id = check_session_user_id($_SESSION['user_id'], './login_top.php');

// データベース接続
$link = get_db_connect();
// ユーザ名を取得できたか確認
$user_name =  check_user_name($link, $user_id, '../logout.php');
// データベース切断
close_db_connect($link);


//shopユーザーを拒否(shopページに移動)
rejection_shop_user($user_name, '../shop/insert_cart.php');
//adminユーザーを拒否(adminページに移動)
rejection_admin_user($user_name, '../management/item_management.php');
}


// セッション変数からログインエラーフラグを確認
if (isset($_SESSION['login_err_flag']) === TRUE) {
   // ログインエラーフラグ取得
   $login_err_flag = $_SESSION['login_err_flag'];
   // エラー表示は1度だけのため、フラグをFALSEへ変更
   $_SESSION['login_err_flag'] = FALSE;
} else {
   // セッション変数が存在しなければエラーフラグはFALSE
   $login_err_flag = FALSE;
}



// Cookie情報からユーザー名を取得
if (isset($_COOKIE['user_name']) === TRUE) {
   $user_name = $_COOKIE['user_name'];
} else {
   $user_name = '';
}
// 特殊文字をHTMLエンティティに変換
$user_name = entity_str($user_name);

include_once '../../include/view/login/login_top.php';

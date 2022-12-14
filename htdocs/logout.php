<?php
/*
*  ログアウト処理
*/
require_once '../include/conf/const.php';
require_once '../include/model/common/common_func.php';
// セッション開始
session_start();
// セッション名取得 ※デフォルトはPHPSESSID
$session_name = session_name();
// セッション変数を全て削除
$_SESSION = [];
 
// ユーザのCookieに保存されているセッションIDを削除
if (isset($_COOKIE[$session_name]) === TRUE) {
  
  // sessionに関連する設定を取得
  $params = session_get_cookie_params();

  // sessionに利用しているクッキーの有効期限を過去に設定することで無効化
  setcookie($session_name, '', time() - 60*60*24,
    $params["path"], $params["domain"],
    $params["secure"], $params["httponly"]
  );
}
 
// セッションIDを無効化
session_destroy();
// ログアウトの処理が完了したらログインページへリダイレクト
header('Location: ./login/login_top.php');
exit;
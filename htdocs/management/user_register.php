<?php
// 設定ファイル読み込み
require_once '../../include/conf/const.php';
// 関数ファイル読み込み
require_once '../../include/model/common/common_func.php';
require_once '../../include/model/common/match_func.php';
require_once '../../include/model/login/login_func.php';
require_once '../../include/model/management/user_func.php';
require_once '../../include/model/management/management_func.php';


/**
 *ログイン確認 
 */
if (isset($_SESSION['user_id']) === TRUE) {
  // セッション開始
  session_start();

  //user_id取得
  $user_id = $_SESSION['user_id'];

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
}

/**
 * ユーザ登録
 */
$err_msg = []; // エラーメッセージ用配列
$match_err_messages = []; //マッチエラー用配列

// リクエストメソッド取得
$request_method = get_request_method();

if ($request_method === 'POST') {

  // DB接続
  $link = get_db_connect();

  // POST値取得
  //viewから取得したvalue情報をデータベースに追加
  $user_name = get_post_data('user_name'); //名前
  $password = get_post_data('password'); //パスワード
  $user_created_date = date('Y-m-d H:i:s'); //登録日時
  $user_updated_date = date('Y-m-d H:i:s'); //更新日時
  $user_id = get_id($link, 'user_id', 't25_user_table'); //id取得

  //入力値チェック
  $match_err_messages[] = match_user_name($link, $user_name);
  $match_err_messages[] = match_password($password);

  //空要素をなくす
  $match_err_messages = array_filter($match_err_messages);

  // 更新系の処理を行う前にトランザクション開始(オートコミットをオフ）
  mysqli_commit_off($link);

  if (count($match_err_messages) !== 0) {
    $err_msg[] = 'マッチ失敗';
  }

  //新規ユーザー追加
  if (insert_user_table($link, $user_id, $user_name, $password, $user_created_date, $user_updated_date) === FALSE) {
    $err_msg[] = 'INSERT_USER失敗:';
  }

  if (transaction_success_or_failure($link, $err_msg) === TRUE) {
    $_SESSION['success_message'] = '登録完了';//完了メッセージ
    header('Location: ./user_register.php');
    exit;
  }


  // DB切断
  close_db_connect($link);
}

// 新規追加テンプレートファイル読み込み
include_once '../../include/view/management/user_register.php';

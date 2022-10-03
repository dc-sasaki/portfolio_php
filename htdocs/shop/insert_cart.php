<?php
require_once '../../include/conf/const.php';
require_once '../../include/model/common/common_func.php';
require_once '../../include/model/common/match_func.php';
require_once '../../include/model/login/login_func.php';
require_once '../../include/model/management/management_func.php';
require_once '../../include/model/management/user_func.php';
require_once '../../include/model/shop/cart_func.php';

/**
 *ログイン確認 
 */

// セッション開始
session_start();
// セッション変数からuser_id取得
$user_id = check_session_user_id($_SESSION['user_id'], '../login/login_top.php');

// データベース接続
$link = get_db_connect();
// ユーザ名を取得できたか確認
$user_name =  check_user_name($link, $user_id, '../logout.php');
// データベース切断
close_db_connect($link);

//adminユーザーを拒否(adminページに移動)
rejection_admin_user($user_name, '../management/item_management.php');

$err_msg = []; // エラーメッセージ用配列
$match_err_messages = []; //マッチエラー用配列

// リクエストメソッド取得
$request_method = get_request_method();

/**
 * カート追加
 */
if ($request_method === 'POST' && get_post_data('sql_kind') === 'insert_cart') {

   // DB接続
   $link = get_db_connect();

   //viewから取得したvalue情報をデータベースに追加
   $item_id = get_post_data('item_id'); //アイテムID
   $amount = 1; //在庫
   $cart_created_date = date('Y-m-d H:i:s'); //登録日時
   $cart_updated_date = date('Y-m-d H:i:s'); //更新日時

   //入力値チェック
   $match_err_messages[] = match_item_id($item_id);
   $match_err_messages[] = match_public_item_id($link, $item_id);

   //空要素をなくす
   $match_err_messages = array_filter($match_err_messages);

   mysqli_commit_off($link);

   if (count($match_err_messages) !== 0) {
      $err_msg[] = 'マッチ失敗';
   }

   $cart_id = get_use_cart_id($link, $user_id, $item_id);

   //card_idが発行された時
   if (isset($cart_id) === TRUE) {
      if (add1_cart_table($link, $cart_id, $cart_updated_date) === FALSE) {
         $err_msg[] = 'UPDATE失敗';
      }
   //card_idが発行されていない時
   } else {
      //カードID発行
      $cart_id = get_id($link, 'cart_id', 't25_cart_table');
      if (insert_cart_table($link, $cart_id, $user_id, $item_id, $amount, $cart_created_date, $cart_updated_date) === FALSE) {
         $err_msg[] = 'INSERT失敗';
      }
   }

   if (transaction_success_or_failure($link, $err_msg) === TRUE) {
      $_SESSION['success_message'] = '追加完了';//完了メッセージ
      header('Location: ./insert_cart.php');
      exit;
   }
   // DB切断
   close_db_connect($link);
}

/**
 * 商品を表示する
 */
$link = get_db_connect();
//商品一覧取得
$get_subtract_item_table = get_subtract_item_table($link);

close_db_connect($link);

// 新規追加テンプレートファイル読み込み
include_once '../../include/view/shop/insert_cart.php';

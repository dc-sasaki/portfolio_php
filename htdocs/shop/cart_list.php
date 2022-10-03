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


// ログイン済みの場合、ホームページへリダイレクト

//adminユーザーを拒否(adminページに移動)
rejection_admin_user($user_name, '../management/item_management.php');

/**
 * 商品のリスト
 */

$err_msg = []; // エラーメッセージ用配列
$match_err_messages = []; //マッチエラー用配列
// リクエストメソッド取得
$request_method = get_request_method();

/**
 * カートの商品削除
 */
if ($request_method === 'POST' && get_post_data('sql_kind') === 'delete') {
   // DB接続
   $link = get_db_connect();

   $item_id = get_post_data('item_id'); //アイテムID

   $match_err_messages[] = match_item_id($item_id);

   //空要素をなくす
   $match_err_messages = array_filter($match_err_messages);

   mysqli_commit_off($link);

   if (count($match_err_messages) !== 0) {
      $err_msg[] = 'マッチ失敗';
   }

   if (delete_cart_table($link, $user_id, $item_id) !== TRUE) {
      $err_msg[] = 'DELETE失敗:';
   }

   if (transaction_success_or_failure($link, $err_msg) === TRUE) {
      $_SESSION['success_message'] = '削除完了';//完了メッセージ
      header('Location: ./cart_list.php');
      exit;
   }

   // DB切断
   close_db_connect($link);
}

/**
 * カートの商品個数変更
 */
if ($request_method === 'POST' && get_post_data('sql_kind') === 'update') {
   // DB接続
   $link = get_db_connect();

   $item_id = get_post_data('item_id'); //アイテムID
   $amount = (int)get_post_data('update_amount'); //カートの商品個数
   $cart_updated_date = date('Y-m-d H:i:s'); //更新日時
   $before_amount = (int)get_before_amount($link, $item_id);
   $get_remaining_stock = (int)get_remaining_stock($link, $user_id, $item_id);

   $match_err_messages[] = match_buy_stock($amount);
   $match_err_messages[] = ($get_remaining_stock + $before_amount) <= $amount ? '購入数が多すぎます' : NULL;

   //空要素をなくす
   $match_err_messages = array_filter($match_err_messages);

   mysqli_commit_off($link);

   if (count($match_err_messages) !== 0) {
      $err_msg[] = 'マッチ失敗';
   }

   if (change_cart_table($link, $user_id, $amount, $item_id, $cart_updated_date) === FALSE) {
      $err_msg[] = 'UPDATE_CART失敗:';
   }

   if (transaction_success_or_failure($link, $err_msg) === TRUE) {
      $_SESSION['success_message'] = '更新完了';//完了メッセージ
      header('Location: ./cart_list.php');
      exit;
   }

   // DB切断
   close_db_connect($link);
}

/**
 * カートの商品購入
 */
if ($request_method === 'POST' && get_post_data('sql_kind') === 'buy_cart_item') {
   // DB接続
   $link = get_db_connect();

   //購入商品表示のため先に取得
   $get_subtract_item_table = get_user_subtract_item_table($link, $user_id);
   $total_price = (int)get_total_price($link, $user_id);
   $total_price = $total_price !== 0 ? $total_price : 0;


   mysqli_commit_off($link);

   //在庫から引く
   if (subtract_stock($link, $user_id) === FALSE) {
      $err_msg[] = 'UPDATE_STOCK失敗:';
   }

   //カートを削除
   if (delete_user_cart($link, $user_id) === FALSE) {
      $err_msg[] = 'DELETE_CART失敗:';
   }

   if (transaction_success_or_failure($link, $err_msg) === TRUE) {
      include_once '../../include/view/shop/cart_list_result.php';
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
$get_subtract_item_table = get_user_subtract_item_table($link, $user_id);

$total_price = (int)get_total_price($link, $user_id);
$total_price = $total_price !== 0 ? $total_price : 0;

close_db_connect($link);

// 新規追加テンプレートファイル読み込み
include_once '../../include/view/shop/cart_list.php';

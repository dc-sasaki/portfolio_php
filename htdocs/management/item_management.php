<?php
// 設定ファイル読み込み
require_once '../../include/conf/const.php';
// 関数ファイル読み込み
require_once '../../include/model/common/common_func.php';
require_once '../../include/model/common/match_func.php';
require_once '../../include/model/login/login_func.php';
require_once '../../include/model/management/management_func.php';
require_once '../../include/model/management/user_func.php';

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

//shopユーザーを拒否
rejection_shop_user($user_name, '../login/login_top.php');

$err_msg = []; // エラーメッセージ用配列
$match_err_messages = []; //マッチエラー用配列

$request_method = get_request_method(); // リクエストメソッド取得

/**
 * 商品追加
 */
if ($request_method === 'POST' && get_post_data('sql_kind') === 'insert') {

  // DB接続
  $link = get_db_connect();

  $item_id = get_id($link, 'item_id', 't25_item_table');//商品ID
  $item_name = get_post_data('item_name'); //商品名
  $price = get_post_data('price'); //価格
  $stock = get_post_data('item_stock'); //在庫
  $item_img_file = get_file_data('item_image');//画像ファイル
  $new_img_name = create_image_name($item_img_file);
  $status = get_post_data('status'); //公開(0)or非公開(1)
  $item_created_date = date('Y-m-d H:i:s'); //登録日時
  $item_updated_date = date('Y-m-d H:i:s'); //更新日時
  
  
  //入力値チェック
  $match_err_messages[] = match_item_name($item_name);
  $match_err_messages[] = match_item_price($price);
  $match_err_messages[] = match_item_stock($stock);
  $match_err_messages[] = match_item_img($item_img_file['name']);
  $match_err_messages[] = match_item_status($status);

  //空要素をなくす
  $match_err_messages = array_filter($match_err_messages);

  // 更新系の処理を行う前にトランザクション開始(オートコミットをオフ）
  mysqli_commit_off($link);


  if (count($match_err_messages) !== 0) {
    $err_msg[] = 'マッチ失敗';
  }

  if (insert_item_table($link, $item_id, $item_name, $price, $new_img_name, $status, $item_created_date, $item_updated_date) === FALSE) {
    $err_msg[] = 'INSERT_ITEM失敗:';
  }

  if (insert_stock_table($link, $item_id, $stock, $item_created_date, $item_updated_date) === FALSE) {
    $err_msg[] = 'INSERT_STOCK失敗:';
  }

  if (transaction_success_or_failure($link, $err_msg) === TRUE) {
    insert_image_file($item_img_file, $new_img_name);
    $_SESSION['success_message'] = '商品追加完了';//完了メッセージ
    header('Location: ./item_management.php');
		exit;
    
  }

  // DB切断
  close_db_connect($link);
}

/**
 * 在庫更新
 */
if ($request_method === 'POST' && get_post_data('sql_kind') === 'update') {

  // DB接続
  $link = get_db_connect();

  $item_id = get_post_data('item_id'); //アイテムID
  $stock = get_post_data('update_stock'); //在庫
  $stock_updated_date = date('Y-m-d H:i:s'); //更新日時

  $match_err_messages[] = match_item_stock($stock);

  //空要素をなくす
  $match_err_messages = array_filter($match_err_messages);



  // 更新系の処理を行う前にトランザクション開始(オートコミットをオフ）
  mysqli_commit_off($link);



  if (count($match_err_messages) !== 0) {
    $err_msg[] = 'マッチ失敗';
  }

  if (update_stock_table($link, $item_id, $stock, $stock_updated_date) === FALSE) {
    $err_msg[] = 'UPDATE_STOCK失敗:';
  }

  if (transaction_success_or_failure($link, $err_msg) === TRUE) {
    $_SESSION['success_message'] = '在庫変更完了';//完了メッセージ
    header('Location: ./item_management.php');
		exit;
  }

  // DB切断
  close_db_connect($link);
}

/**
 * ステータス更新
 */
if ($request_method === 'POST'  && get_post_data('sql_kind') === 'change') {

  // DB接続
  $link = get_db_connect();

  $item_id = get_post_data('item_id'); //アイテムID
  $status = get_post_data('change_status'); //公開(0)or非公開(1)
  $item_updated_date = date('Y-m-d H:i:s'); //更新日時

  $match_err_messages[] = match_item_status($status);

  //空要素をなくす
  $match_err_messages = array_filter($match_err_messages);

  // 更新系の処理を行う前にトランザクション開始(オートコミットをオフ）
  mysqli_commit_off($link);


  if (count($match_err_messages) !== 0) {
    $err_msg[] = 'マッチ失敗';
  }

  if (change_item_table($link, $item_id, $status, $item_updated_date) === FALSE) {
    $err_msg[] = 'UPDATE_ITEM失敗:';
  }

  if (transaction_success_or_failure($link, $err_msg) === TRUE) {
    $_SESSION['success_message'] = 'ステータス変更完了';//完了メッセージ
    header('Location: ./item_management.php');
		exit;
  }

  // DB切断
  close_db_connect($link);
}

/**
 * 商品削除
 */
if ($request_method === 'POST'  && get_post_data('sql_kind') === 'delete') {

  // DB接続
  $link = get_db_connect();

  //画像の削除
  $item_image = get_post_data('item_image');
  $item_id = get_post_data('item_id'); //アイテムID

  // 更新系の処理を行う前にトランザクション開始(オートコミットをオフ）
  mysqli_commit_off($link);

  //データベースから削除
  if (delete_item_table($link, $item_id) === FALSE) {
    $err_msg[] = 'DELETE_ITEM失敗:';
  }

  if (transaction_success_or_failure($link, $err_msg) === TRUE) {
    delete_item_image($item_image);
    $_SESSION['success_message'] = '商品削除完了';//完了メッセージ
    header('Location: ./item_management.php');
		exit;
  }

  // DB切断
  close_db_connect($link);
}


$link = get_db_connect();
//商品一覧取得
$get_item_list = get_item_table_list($link);

close_db_connect($link);


// 新規追加テンプレートファイル読み込み
include_once '../../include/view/management/item_management.php';

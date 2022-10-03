<?php
// 設定ファイル読み込み
require_once '../../include/conf/const.php';
// 関数ファイル読み込み
require_once '../../include/model/common/common_func.php';
require_once '../../include/model/login/login_func.php';
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

/**
 * ユーザー一覧取得
 */
// リクエストメソッド取得
$request_method = get_request_method();

  $link = get_db_connect();
  //ユーザー一覧取得
  $get_user_all_list = get_user_table_all_list($link);

  close_db_connect($link);

// 新規追加テンプレートファイル読み込み
include_once '../../include/view/management/user_list.php';

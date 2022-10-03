<?php

/**
 * 新規ユーザーを追加する
 *
 * @param mysqli $link DBハンドル
 * @param int user_id
 * @param string user_name
 * @param string password
 * @param string $user_created_date 登録日時
 * @param string $user_updated_date 更新日時   
 * @return bool
 */
function insert_user_table($link, $user_id, $user_name, $password, $user_created_date, $user_updated_date) {
    // 挿入情報をまとめる
    $data = [
        'user_id' => $user_id,
        'user_name' => '\'' . $user_name . '\'',
        'password' => '\'' . $password . '\'',
        'user_created_date' => '\'' . $user_created_date . '\'',
        'user_update_date' => '\'' . $user_updated_date . '\''
    ];
    // SQL生成
    $sql = 'INSERT INTO t25_user_table (user_id, user_name, password, user_created_date, user_updated_date) VALUES(' . implode(',', $data) . ')';

    // クエリ実行
    return insert_db($link, $sql);
}


/**
 * ユーザー名の一覧を取得する
 *
 * @param mysqli $link DBハンドル
 * @return array ユーザーの一覧配列データ
 */
function get_user_table_list($link) {
    // SQL生成
    $sql = 'SELECT user_name, password FROM t25_user_table';

    // クエリ実行
    return get_as_array($link, $sql);
}

/**
 * ユーザーのid,name,password一覧を取得する
 *
 * @param mysqli $link DBハンドル
 * @return array ユーザーの一覧配列データ
 */
function get_user_table_all_list($link) {
    // SQL生成
    $sql = 'SELECT user_id, user_name, password, user_created_date, user_updated_date FROM t25_user_table WHERE user_id >= 2';

    // クエリ実行
    return get_as_array($link, $sql);
}


/**
 *user_idをuser_name,passwordから取得する
 *
 * @param mysqli $link DBハンドル
 * @return array ユーザーの一覧配列データ
 */
function get_login_user($link, $user_name, $password) {
    // SQL生成
    $sql = 'SELECT user_id FROM t25_user_table
       WHERE user_name =\'' . $user_name . '\' AND password =\'' . $password . '\'';

    // クエリ実行
    $data = get_as_array($link, $sql);

    return $data[0]['user_id'];;
}


/**
 * ユーザーの一覧配列データを取得
 *
 * @param mysqli $link DBハンドル
 * @return array ユーザーの一覧配列データ
 */
function get_user_list($link) {

    // user_idからユーザ名を取得するSQL
    $sql = 'SELECT user_name FROM t25_user_table';

    // クエリ実行
    return get_as_array($link, $sql);
}

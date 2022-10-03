<?php
require_once '../../include/conf/const.php';

/**
 * ショップユーザーが入れないサイトの時に拒否する
 * 
 * @param string user_name
 * @param string ログインページへの相対パス
 * @return
 */
function rejection_shop_user($user_name, $login_page_path) {
    if ($user_name !== ADMIN_USER) {
        header('Location: ' . $login_page_path);
        exit;
    }
}

/**
 * 管理ユーザーが入れないサイトの時に拒否する
 * 
 * @param string user_name
 * @param string ログインページへの相対パス
 * @return
 */
function rejection_admin_user($user_name, $login_page_path) {
    if ($user_name === ADMIN_USER) {
        header('Location: ' . $login_page_path);
        exit;
    }
}

/**
 * セッションからユーザーIDを取得できるか確認する
 * 
 * @param string session_user_id
 * @param string ログインページへの相対パス
 * @return string user_id
 */
function check_session_user_id($session_user_id, $login_page_path) {
    // セッション変数からuser_id取得
    if (isset($session_user_id) === TRUE) {
        $user_id = $session_user_id;
    } else {
        // 非ログインの場合、ログインページへリダイレクト
        header('Location: ' . $login_page_path);
        exit;
    }
    return $user_id;
}

/**
 * ユーザーIDからユーザー名を取得できるか確認する
 * 
 * @param mysqli $link DBハンドル
 * @param string user_id
 * @param string ログインページへの相対パス
 * @return string user_name
 */
function check_user_name($link, $user_id, $logout_page_path) {
    // user_idからユーザ名を取得するSQL
    $sql = 'SELECT user_name FROM t25_user_table WHERE user_id = ' . $user_id;

    $data = get_as_array($link, $sql);

    // ユーザ名が取得できない場合、ログアウト処理へリダイレクト
    if (isset($data[0]['user_name']) === FALSE) {

        header('Location: ' . $logout_page_path);
        exit;
    }
    return $data[0]['user_name'];
}

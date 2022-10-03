<?php

/**
 * 特殊文字をHTMLエンティティに変換する
 * @param string  $string 変換前文字
 * @return string 変換後文字
 */
function entity_str($string) {
    return htmlspecialchars($string, ENT_QUOTES, HTML_CHARACTER_SET);
}

/**
 * 特殊文字をHTMLエンティティに変換する(2次元配列の値)
 * @param array  $assoc_array 変換前配列
 * @return array 変換後配列
 */
function entity_assoc_array($assoc_array) {

    foreach ($assoc_array as $key => $value) {

        foreach ($value as $keys => $values) {
            // 特殊文字をHTMLエンティティに変換
            $assoc_array[$key][$keys] = entity_str($values);
        }
    }

    return $assoc_array;
}

/**
 * DBハンドルを取得
 * @return mysqli $link DBハンドル
 */
function get_db_connect() {
    // コネクション取得
    if (!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME)) {
        die('error: ' . mysqli_connect_error());
    }

    // 文字コードセット
    mysqli_set_charset($link, DB_CHARACTER_SET);

    return $link;
}

/**
 * DBとのコネクション切断
 * @param mysqli $link DBハンドル
 */
function close_db_connect($link) {
    // 接続を閉じる
    mysqli_close($link);
}

/**
 * クエリを実行しその結果を配列で取得する
 *
 * @param mysqli  $link DBハンドル
 * @param string  $sql SQL文
 * @return array 結果配列データ
 */
function get_as_array($link, $sql) {
    // 返却用配列
    $data = [];
    // クエリを実行する
    if ($result = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            // １件ずつ取り出す
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        // 結果セットを開放
        mysqli_free_result($result);
    }
    return entity_assoc_array($data);
}

/**
 * insertを実行する
 *
 * @param mysqli $link DBハンドル
 * @param string SQL文
 * @return bool
 */
function insert_db($link, $sql) {
    // クエリを実行する
    if (mysqli_query($link, $sql) === TRUE) {
        return TRUE;
    } else {
        return FALSE;
    }
}

/**
 * リクエストメソッドを取得
 * @return string GET/POST/PUTなど
 */
function get_request_method() {
    return $_SERVER['REQUEST_METHOD'];
}

/**
 * POSTデータを取得
 * @param string $key 配列キー
 * @return string POST値
 */
function get_post_data($key) {
    $string = '';
    if (isset($_POST[$key]) === TRUE) {
        $string = $_POST[$key];
    }
    return $string;
}


/**
 * まだ使用していないidを取得する
 * 
 * @param mysqli $link DBハンドル
 * @return int $item_id アイテムID
 */
function get_id($link, $id, $table) {
    $sql = 'SELECT MAX(' . $id . ') AS max_id FROM ' . $table;

    $array = get_as_array($link, $sql);

    if (isset($array[0]['max_id']) === TRUE) {
        $item_id = (int)$array[0]['max_id'] + 1;
    } else {
        $item_id = 1;
    }
    return $item_id;
}

/**
 * 更新系の処理を行う前にトランザクション開始(オートコミットをオフ）
 * 
 * @param mysqli $link DBハンドル
 */
function mysqli_commit_off($link) {
    mysqli_autocommit($link, false);
}

/**
 * トランザクション成否判定
 * 
 * @param mysqli $link DBハンドル
 * @param array $err_msg
 * @return bool トランザクション成否判定
 */
function transaction_success_or_failure($link, $err_msg) { // トランザクション成否判定
    if (count($err_msg) === 0) {
        // 処理確定
        mysqli_commit($link);
        return TRUE;
    } else {
        // 処理取消
        mysqli_rollback($link);
        return FALSE;
    }
}

<?php

/**
 * 商品の一覧を取得する
 *
 * @param mysqli $link DBハンドル
 * @return array 商品一覧配列データ
 */
function get_item_table_list($link) {
    // SQL生成
    $sql = 'SELECT item_tab.item_id, item_tab.item_img, item_tab.item_name, item_tab.price, stock_tab.stock, item_tab.status FROM t25_item_table AS item_tab INNER JOIN t25_stock_table AS stock_tab ON item_tab.item_id = stock_tab.item_id';

    // クエリ実行
    return get_as_array($link, $sql);
}

/**
 * 新規商品を追加する
 *
 * @param mysqli $link DBハンドル
 * @param int $item_id 商品ID
 * @param string $item_name 商品名
 * @param int $price 価格
 * @param string $item_img 商品画像名
 * @param int $status ステータス
 * @param string $item_created_date 登録日時
 * @param string $item_updated_date 更新日時   
 * @return bool
 */
function insert_item_table($link, $item_id, $item_name, $price, $item_img, $status, $item_created_date, $item_updated_date) {
    // 挿入情報をまとめる
    $data = [
        'item_id' => $item_id,
        'item_name' => '\'' . $item_name . '\'',
        'price' => $price,
        'item_img'  => '\'' . $item_img . '\'',
        'status' => $status,
        'item_created_date' => '\'' . $item_created_date . '\'',
        'item_updated_date' => '\'' . $item_updated_date . '\''
    ];

    // SQL生成
    $sql = 'INSERT INTO t25_item_table (item_id, item_name, price, item_img, status, item_created_date, item_updated_date) VALUES(' . implode(',', $data) . ')';

    // クエリ実行
    return insert_db($link, $sql);
}

/**
 * 新規商品在庫を追加する
 *
 * @param mysqli $link DBハンドル
 * @param int $item_id 商品ID
 * @param int $stock 在庫数
 * @param string $item_created_date 登録日時
 * @param string $item_updated_date 更新日時   
 * @return bool
 */
function insert_stock_table($link, $item_id, $stock, $stock_created_date, $stock_updated_date) {

    // 挿入情報をまとめる
    $data = [
        'item_id' => $item_id,
        'stock' => $stock,
        'stock_created_date' => '\'' . $stock_created_date . '\'',
        'stock_updated_date' => '\'' . $stock_updated_date . '\''
    ];

    // SQL生成
    $sql = 'INSERT INTO t25_stock_table (item_id, stock, stock_created_date, stock_updated_date) VALUES(' . implode(',', $data) . ')';

    // クエリ実行
    return insert_db($link, $sql);
}

/**
 * 商品在庫を変更する
 * @param mysqli $link DBハンドル
 * @param int $item_id 商品ID
 * @param int $stock 在庫数
 * @param string $stock_updated_date 更新日時   
 * @return bool
 */
function update_stock_table($link, $item_id, $stock, $stock_updated_date) {
    // SQL生成
    $sql = 'UPDATE t25_stock_table SET stock = ' . $stock . ', stock_updated_date = ' . '\'' . $stock_updated_date . '\'' . ' WHERE item_id = ' . $item_id;
    // クエリ実行
    return insert_db($link, $sql);
}

/**
 * 商品ステータスを変更する
 * @param mysqli $link DBハンドル
 * @param int $item_id 商品ID
 * @param int $status ステータス
 * @param string $item_updated_date 更新日時   
 * @return bool
 */
function change_item_table($link, $item_id, $status, $item_updated_date) {
    // SQL生成
    $sql = 'UPDATE t25_item_table SET status= ' . $status . ', item_updated_date = ' . '\'' . $item_updated_date . '\'' . ' WHERE item_id = ' . $item_id;
    // クエリ実行
    return insert_db($link, $sql);
}

/**
 * 商品を削除する
 * @param mysqli $link DBハンドル
 * @param int $item_id 商品ID 
 * @return bool
 */
function delete_item_table($link, $item_id) {
    // SQL生成
    $sql = 'DELETE FROM t25_stock_table WHERE item_id = ' . $item_id;
    // クエリ実行
    if (insert_db($link, $sql) === TRUE) {
        // SQL生成
        $sql = 'DELETE FROM t25_item_table WHERE item_id = ' . $item_id;
        // クエリ実行
        return insert_db($link, $sql);
    } else {
        return FALSE;
    }
}


/**
 * ファイルを取得
 * 
 * @param string 元のファイル名
 * @return string 生成したファイル名
 */
function get_file_data($key) {
    foreach ($_FILES[$key] as $f_key => $value) {
        $file_data[$f_key] = $value;
    }
    return $file_data;
}


/**
 * 画像ファイルをimagesフォルダに保存
 * 
 * @param string 元のファイル名
 * @return string 生成したファイル名
 */
function insert_image_file($img_file, $new_filename) {
    // ファイルの保存先(要使用先のファイルパス確認)
    $saveDir = '../../include/view/images/';

    // ファイルを一時フォルダから指定したディレクトリに移動します
    move_uploaded_file($img_file['tmp_name'], $saveDir . $new_filename);
}

/**
 * 画像ファイルの名前を生成
 * 
 * @param string 元のファイル名
 * @return string 生成したファイル名
 */
function create_image_name($img_file) {
    // ファイル名を取得します
    $filename = $img_file['name'];

    //拡張子取得
    $ext = substr($filename, strrpos($filename, '.') + 1);

    //新規ファイル名の作成
    $uniqu = uniqid(mt_rand() . '_');

    //ファイル名 . 拡張子
    $new_filename = $uniqu . '.' . $ext;

    return $new_filename;
}

/**
 * 画像ファイルを削除
 * 
 * @param string ファイル名
 */
function delete_item_image($item_image) {
    $file = '../../include/view/images/' . $item_image;
    if (isset($item_image) === TRUE) {
        unlink($file);
    }
}

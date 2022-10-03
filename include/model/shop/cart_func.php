<?php

/**
 * 新規商品をカートに追加する
 *
 * @param mysqli $link DBハンドル
 * @param int $item_id 商品ID
 * @param int $stock 在庫数
 * @param string $item_created_date 登録日時
 * @param string $item_updated_date 更新日時   
 * @return bool
 */
function insert_cart_table($link, $cart_id, $user_id, $item_id, $amount, $cart_created_date, $cart_updated_date)
{

    // 挿入情報をまとめる
    $data = [
        'cart_id' => $cart_id,
        'user_id' => $user_id,
        'item_id' => $item_id,
        'amount' => $amount,
        'cart_created_date' => '\'' . $cart_created_date . '\'',
        'cart_updated_date' => '\'' . $cart_updated_date . '\''
    ];

    // SQL生成
    $sql = 'INSERT INTO t25_cart_table (cart_id, user_id, item_id, amount, cart_created_date, cart_updated_date) VALUES(' . implode(',', $data) . ')';

    // クエリ実行
    return insert_db($link, $sql);
}


/**
 * カート在庫に+1する
 * @param mysqli $link DBハンドル
 * @param int $item_id 商品ID
 * @param int $status ステータス
 * @param string $item_updated_date 更新日時   
 * @return bool
 */
function add1_cart_table($link, $cart_id, $cart_updated_date)
{
    // SQL生成
    $sql = 'UPDATE t25_cart_table SET amount = amount + 1, cart_updated_date = ' . '\'' . $cart_updated_date . '\'' . ' WHERE cart_id = ' . $cart_id;
    // クエリ実行
    return insert_db($link, $sql);
}

/**
 * カート在庫を変更する
 * @param mysqli $link DBハンドル
 * @param int $item_id 商品ID
 * @param int $status ステータス
 * @param string $item_updated_date 更新日時   
 * @return bool
 */
function change_cart_table($link, $user_id, $amount, $item_id, $cart_updated_date)
{
    // SQL生成
    $sql = 'UPDATE t25_cart_table SET amount = ' . $amount . ', cart_updated_date = ' . '\'' . $cart_updated_date . '\'' . ' WHERE user_id = ' . $user_id . ' AND item_id = ' . $item_id;
    // クエリ実行
    return insert_db($link, $sql);
}

/**
 * 使用中のカートIDを取得
 *
 * @param mysqli $link DBハンドル
 * @param int user_id 
 * @param int item_id
 * @return int cart_id
 */
function get_use_cart_id($link, $user_id, $item_id)
{
    // SQL生成
    $sql = 'SELECT cart_id FROM t25_cart_table WHERE user_id = ' . $user_id . ' AND item_id = ' . $item_id;
    // クエリ実行
    $data = get_as_array($link, $sql);

    return $data[0]['cart_id'];
}

/**
 * 在庫からカートの個数を除いた商品の一覧を取得する
 * (item_id, item_img, item_name, price, stock, sum_amount(全ユーザのカートの特定の商品の合計), remaining_stock(在庫 - カートの商品))
 * 
 * @param mysqli $link DBハンドル
 * @return array 商品一覧配列データ
 */
function get_subtract_item_table($link)
{
    // SQL生成
    $sql = 'SELECT t25_item_table.item_id, t25_item_table.item_img, t25_item_table.item_name, t25_item_table.price, t25_stock_table.stock, ifnull(cart.sum_amount,\'0\') AS sum_amount, ( t25_stock_table.stock - ifnull(cart.sum_amount,\'0\') ) AS remaining_stock FROM t25_item_table INNER JOIN t25_stock_table ON t25_item_table.item_id = t25_stock_table.item_id LEFT JOIN( SELECT t25_cart_table.item_id, SUM(t25_cart_table.amount) AS sum_amount FROM t25_cart_table GROUP BY item_id ) AS cart ON t25_item_table.item_id = cart.item_id WHERE t25_item_table.status = 1';
    // クエリ実行
    return get_as_array($link, $sql);
}


/**
 * 指定したユーザのカートに入っている商品の一覧を取得する
 *(item_id, item_img, item_name, price, amount, stock, sum_amount(全ユーザのカートの特定の商品の合計), remaining_stock(在庫 - カートの商品)

 * @param mysqli $link DBハンドル
 * @return array 商品一覧配列データ
 */
function get_user_subtract_item_table($link, $user_id)
{
    // SQL生成
    $sql = 'SELECT t25_item_table.item_id, t25_item_table.item_img, t25_item_table.item_name, t25_item_table.price, t25_cart_table.amount, t25_stock_table.stock, cart.sum_amount, (t25_stock_table.stock - cart.sum_amount) as remaining_stock FROM t25_item_table INNER JOIN t25_stock_table ON t25_item_table.item_id = t25_stock_table.item_id INNER JOIN (SELECT t25_cart_table.item_id, SUM(t25_cart_table.amount) as sum_amount FROM t25_cart_table GROUP BY item_id) as cart ON t25_item_table.item_id = cart.item_id INNER JOIN t25_cart_table ON t25_item_table.item_id = t25_cart_table.item_id WHERE t25_cart_table.user_id = ' . $user_id;
    // クエリ実行
    return get_as_array($link, $sql);
}

/**
 * 変更前のカートの商品数を取得する
 * 
 * @param mysqli $link DBハンドル
 * @return string 商品数
 */
function get_before_amount($link, $item_id)
{
    // SQL生成
    $sql = 'SELECT amount FROM t25_cart_table WHERE item_id = ' . $item_id;
    // クエリ実行
    $data = get_as_array($link, $sql);

    return $data[0]['amount'];
}

/**
 * 在庫からカートの個数を除いた商品の一覧を取得する
 *
 * @param mysqli $link DBハンドル
 * @param int user_id 
 * @param int item_id
 * @return string 在庫からカートの商品数を引いた個数
 */
function get_remaining_stock($link, $user_id, $item_id)
{
    // SQL生成
    $sql = 'SELECT (t25_stock_table.stock - cart.sum_amount) as remaining_stock FROM t25_stock_table INNER JOIN (SELECT t25_cart_table.item_id, SUM(t25_cart_table.amount) as sum_amount FROM t25_cart_table GROUP BY item_id) as cart ON t25_stock_table.item_id = cart.item_id INNER JOIN t25_cart_table ON t25_stock_table.item_id = t25_cart_table.item_id WHERE t25_cart_table.user_id = ' . $user_id . ' AND t25_cart_table.item_id = ' . $item_id;
    // クエリ実行
    $data = get_as_array($link, $sql);

    return $data[0]['remaining_stock'];
}

/**
 * カートを削除する
 * @param mysqli $link DBハンドル
 * @param int user_id 
 * @param int item_id
 * @return bool
 */
function delete_cart_table($link, $user_id, $item_id)
{
    // SQL生成
    $sql = 'DELETE FROM t25_cart_table WHERE user_id = ' . $user_id . ' AND item_id = ' . $item_id;
    // クエリ実行
    return insert_db($link, $sql);
}


/**
 * 指定したユーザーがカートに入れた商品の合計金額を取得する
 * 
 * @param mysqli $link DBハンドル
 * @param int user_id
 * 
 */
function get_total_price($link, $user_id)
{
    $sql = 'SELECT SUM(sum_price) AS total_price FROM ( SELECT t25_cart_table.amount, t25_item_table.price, t25_cart_table.amount * t25_item_table.price AS sum_price FROM t25_item_table INNER JOIN t25_cart_table ON t25_item_table.item_id = t25_cart_table.item_id WHERE t25_cart_table.user_id = ' . $user_id . ' ) AS price_tableF';
    // クエリ実行
    $data = get_as_array($link, $sql);

    return $data[0]['total_price'];
}

/**
 * 購入した商品の個数を在庫から引く
 * 
 * @param mysqli $link DBハンドル
 * @param int user_id
 * 
 */
function subtract_stock($link, $user_id){
    $sql = 'UPDATE t25_stock_table AS stock INNER JOIN t25_cart_table AS cart ON stock.item_id = cart.item_id SET stock.stock = stock.stock - cart.amount WHERE cart.user_id = '.$user_id;

    return insert_db($link, $sql);
}

/**
 * 指定したユーザーのカートをすべて削除
 *
 * @param mysqli $link DBハンドル
 * @param int user_id
 * 
 */
function delete_user_cart($link, $user_id){
       // SQL生成
       $sql = 'DELETE FROM t25_cart_table WHERE user_id = ' . $user_id;
       // クエリ実行
       return insert_db($link, $sql);
}

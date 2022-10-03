<?php

/**
 * 商品IDチェック
 * 
 * @param string $item_name 
 * @return string $match_err_message
 * @return null 
 */
function match_item_id($item_id) {
  $match_err_message = null;
  if (mb_strlen($item_id) === 0) {
    $match_err_message =  'IDを入力してください';
  } else if (preg_match('/^[0-9]{1,11}$/', $item_id) !== 1) {
    $match_err_message = '値段：11桁以内の数値で入力してください';
  }
  return $match_err_message;
}

/**
 * 商品名チェック
 * 
 * @param string $item_name 
 * @return string $match_err_message
 */
function match_item_name($item_name) {
  $match_err_message = null;
  if (mb_strlen($item_name) === 0) {
    $match_err_message =  '商品名を入力してください';
  } else if (preg_match('/^[ぁ-んァ-ヶーa-zA-Z0-9一-龠０-９、。\n\r]{1,100}$/u', $item_name) !== 1) {
    $match_err_message = '商品名：100文字以内で入力してください';
  }
  return $match_err_message;
}

/**
 * 値段チェック
 * 
 * @param int $price
 * @return string $match_err_message
 */
function match_item_price($price) {
  $match_err_message = null;
  if (mb_strlen($price) === 0) {
    $match_err_message =  '値段を入力してください';
  } else if (preg_match('/^[0-9]{1,11}$/', $price) !== 1) {
    $match_err_message = '値段：11桁以内の数値で入力してください';
  }
  return $match_err_message;
}

/**
 * 在庫チェック
 * 
 * @param int $item_stock
 * @return string $match_err_message
 */
function match_item_stock($stock) {
  $match_err_message = null;
  if (mb_strlen($stock) === 0) {
    $match_err_message =  '個数を入力してください';
  } else if (preg_match('/^[0-9]{1,11}$/', $stock) !== 1) {
    $match_err_message = '個数：11桁以内の数値で入力してください';
  }
  return $match_err_message;
}

/**
 * 購入数チェック
 * 
 * @param int $item_stock
 * @return string $match_err_message
 */
function match_buy_stock($item_stock) {
  $match_err_message = null;
  if (mb_strlen($item_stock) === 0) {
    $match_err_message =  '個数を入力してください';
  } else if (preg_match('/^[0]{0,}$/', $item_stock) == 1) {
    $match_err_message = '個数：1以上の数値で入力してください';
  } else if (preg_match('/^[0-9]{1,11}$/', $item_stock) !== 1) {
    $match_err_message = '個数：11桁以内の数値で入力してください';
  }
  return $match_err_message;
}

/**
 * 画像拡張子チェック
 * 
 * @param int $item_img
 * @return string $match_err_message
 */
function match_item_img($item_img) {
  $match_err_message = null;
  //拡張子取得
  $ext = substr($item_img, strrpos($item_img, '.') + 1);
  if (mb_strlen($item_img) === 0) {
    $match_err_message =  '画像を選択してください';
  } else if ($ext === 'png' || $ext === 'jpg' || $ext === 'jpeg') {
    return $match_err_message;
  }
  $match_err_message =  '画像を選択してください';
  return $match_err_message;
}


/**
 * ステータスチェック
 * 
 * @param int $status
 * @return string $match_err_message
 */
function match_item_status($status) {
  $match_err_message = null;
  if (preg_match('/^[0-1]{1}$/', $status) !== 1) {
    $match_err_message = 'ステータス：形式が違います。';
  }
  return $match_err_message;
}

/**
 * ユーザー名チェック
 * 
 * @param int $$user_name
 * @return string $match_err_message
 */
function match_user_name($link, $user_name) {
  $match_err_message = null;
  if (preg_match('/^[a-zA-Z0-9]{6,11}$/', $user_name) !== 1) {
    $match_err_message = 'ユーザー名は6文字以上11文字以内の半角英数字を入力してください';
  } else {
    //使用済みユーザー名
    $sql = 'SELECT user_name FROM t25_user_table WHERE user_name = ' . '\'' . $user_name . '\'';
    // クエリ実行
    $data = get_as_array($link, $sql);
    if (isset($data[0]['user_name']) === TRUE) {
      $match_err_message = 'その名前はすでに使用されています';
    }
  }
  return $match_err_message;
}

/**
 * パスワードチェック
 * 
 * @param int $password
 * @return string $match_err_message
 */
function match_password($password) {
  $match_err_message = null;
  if (preg_match('/^[a-zA-Z0-9]{6,11}$/', $password) !== 1) {
    $match_err_message = 'パスワードは6文字以上11文字以内の半角英数字を入力してください';
  }
  return $match_err_message;
}

/**
 * 非公開の商品のチェック
 * 
 * @param mysqli $link DBハンドル
 * @param int item_id
 * @return bool
 */
function match_public_item_id($link, $item_id) {
  $match_err_message = null;
  // SQL生成
  $sql = 'SELECT status FROM t25_item_table WHERE item_id = ' . $item_id;
  // クエリ実行
  $data = get_as_array($link, $sql);

  if ((int)$data[0]['status'] !== 1) {
    $match_err_message = 'その商品は購入できません';
  }
  return $match_err_message;
}

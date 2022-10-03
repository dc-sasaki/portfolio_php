<!DOCTYPE HTML>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>商品一覧</title>
  <link type="text/css" rel="stylesheet" href="../../include/view/css/common.css">
  <link type="text/css" rel="stylesheet" href="../../include/view/css/shop_header.css">
  <link type="text/css" rel="stylesheet" href="../../include/view/css/insert_cart.css">
</head>

<body>
  <?php include('../../include/view/shop/shop_header.php'); ?>

  <!-- エラーメッセージ表示 -->
  <?php foreach ($match_err_messages as $value) { ?>
    <p class="error"> <?php print $value ?> </p>
  <?php } ?>

  <div class="cart">

    <div class="item-list">
      <?php foreach ($get_subtract_item_table as $value) { ?>
        <div class="item">
          <div class="item-image-frame">
            <img class="item-img" src=<?php print '../../include/view/images/' . $value['item_img']; ?>>
          </div>
          <div class="item-info-frame">
            <div class="item-info">
              <span class="item-name"><?php print $value['item_name'] ?></span>
              <span class="item-price"><?php print $value['price'] . '円' ?></span>
            </div>
            <form method="post">
              <div class="button-wrapper">
                <?php if ((int)$value['remaining_stock'] > 0) { ?>
                  <button class="cart-btn" type="submit" ?>
                    カートに入れる
                  </button>
                <?php } else { ?>
                  <p class="none">売り切れ</p>
                <?php } ?>
              </div>
              <input type="hidden" name="item_id" value=<?php print $value['item_id'] ?>>
              <input type="hidden" name="sql_kind" value="insert_cart">
          </div>
          </form>
        </div>
      <?php } ?>
    </div>

</body>

</html>
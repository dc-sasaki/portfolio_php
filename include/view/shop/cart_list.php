<!DOCTYPE HTML>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>商品一覧</title>
  <link type="text/css" rel="stylesheet" href="../../include/view/css/common.css">
  <link type="text/css" rel="stylesheet" href="../../include/view/css/shop_header.css">
  <link type="text/css" rel="stylesheet" href="../../include/view/css/cart_list.css">
</head>

<body>
  <?php include('../../include/view/shop/shop_header.php'); ?>

  <div class="back_to_cart">
    <a href="./insert_cart.php">カートへ戻る</a>
  </div>

  <div class="cart">
    <section>
      <h2>商品購入</h2>

      <!-- エラーメッセージ表示 -->
      <?php foreach ($match_err_messages as $value) { ?>
        <p class="error"> <?php print $value ?> </p>
      <?php } ?>

      <!-- 完了表示 -->
      <?php if (isset($_SESSION['success_message']) !== null) { ?>
            <p> <?php print $_SESSION['success_message']; ?> </p>
        <?php
            $_SESSION['success_message'] = null;
        } ?>


      <table>
        <tr>
          <th></th>
          <th></th>
          <th></th>
          <th>削除</th>
          <th>価格</th>
          <th>購入数</th>

        </tr>

        <?php foreach ($get_subtract_item_table as $value) { ?>
          <tr>
            <td class="item_img"><img class="img_size" src=<?php print '../../include/view/images/' . $value['item_img']; ?>></td>
            <td class="item_name"><?php print $value['item_name']; ?></td>
            <td class="space"></td>

            <!-- 商品削除 -->
            <form method="post">
              <td class="delete">
                <button class="delete-btn" type="submit">削除する</button>
              </td>
              <input type="hidden" name="item_id" value=<?php print $value['item_id']; ?>>
              <input type="hidden" name="sql_kind" value="delete">
            </form>

            <!-- 価格 -->
            <td class="price"><?php print $value['price'] . '円'; ?></td>

            <!-- 在庫変更 -->
            <form method="post">
              <td class="change">
                <input type="text" class="change_text_width" name="update_amount" value=<?php print $value['amount']; ?>>
                <p>個</p>

                <button class="change-btn" type="submit">変更する</button>
              </td>
              <input type="hidden" name="item_id" value=<?php print $value['item_id']; ?>>
              <input type="hidden" name="remaining_stock" value=<?php print $value['remaining_stock']; ?>>
              <input type="hidden" name="sql_kind" value="update">
            </form>

          </tr>
        <?php } ?>

      </table>
    </section>
    <section>
      <div class='item_price_sum'>
        <?php print '合計：' . $total_price . '円'; ?>
      </div>

      <form method="post">
        <button class="buy-btn" type="submit">購入</button>
        <input type="hidden" name="sql_kind" value="buy_cart_item">
        <form>
    </section>


</body>

</html>
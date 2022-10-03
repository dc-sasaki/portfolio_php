<!DOCTYPE HTML>
<html lang="ja">

<head>
  <meta charset="UTF-8">  
  <title>購入商品</title>
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
      <table>
        <tr>
          <th></th>
          <th></th>
          <th></th>
          <th>価格</th>
          <th>購入数</th>

        </tr>

        <?php foreach ($get_subtract_item_table as $value) { ?>
          <tr>
            <td class="item_img"><img class="img_size" src=<?php print '../../include/view/images/' . $value['item_img']; ?>></td>
            <td class="item_name"><?php print $value['item_name']; ?></td>
            <td class="space"></td>

            <!-- 価格 -->
            <td class="price"><?php print $value['price'] . '円'; ?></td>

            <!-- 在庫 -->
            <td class="change"><?php print $value['amount'] . '個'; ?></td>
          </tr>
        <?php } ?>

      </table>
    </section>
    <section>
      <div class='item_price_sum'>
        <?php print '合計：' . $total_price . '円'; ?>
        <?php print '購入完了'; ?>
      </div>

</body>

</html>
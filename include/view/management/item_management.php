<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>商品登録</title>
    <link type="text/css" rel="stylesheet" href="../../include/view/css/common.css">
    <link type="text/css" rel="stylesheet" href="../../include/view/css/item_management.css">
</head>

<body>
    <section>
        <h1>商品登録</h1>

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

        <a href="../logout.php">ログアウト</a>
        <a href="../../htdocs/management/user_list.php">ユーザー管理ページ</a>

    </section>

    <hr>

    <section>
        <h2>商品追加</h2>
        <form method="post" enctype="multipart/form-data">
            <label for="name">名前：</label>
            <input id="item_name" type="text" name="item_name" value="<?php if (isset($item_name) === TRUE) {
                                                                            print $item_name;
                                                                        } ?>">
            <br>
            <label for="price">値段：</label>
            <input id="price" type="text" name="price" value="<?php if (isset($price) === TRUE) {
                                                                    print $price;
                                                                } ?>">
            <br>

            <label for="stock">個数：</label>
            <input id="stock" type="text" name="item_stock" value="<?php if (isset($item_stock) === TRUE) {
                                                                        print $item_stock;
                                                                    } ?>">
            <br>
            <label for="image">商品画像：</label>
            <input type="file" id="item_image" name="item_image" accept=".jpg, .jpeg, .png">
            <br>

            <label for="status">ステータス：</label>
            <select name="status">
                <option name="status" value="1">公開</option>
                <option name="status" value="0">非公開</option>
                <!-- <option name="status" value="3">テスト用</option> -->
            </select>
            <br>
            <button type="submit">商品追加</button>
            <input type="hidden" name="sql_kind" value="insert">
        </form>



    </section>
    <hr>
    <section class="item_list">
        <h2>商品情報の一覧・変更</h2>
        <table>
            <tr>
                <th>商品番号</th>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価　格</th>
                <th>在庫数</th>
                <th>ステータス</th>
                <th>操作</th>
            </tr>

            <?php foreach ($get_item_list as $value) { ?>
                <tr>
                    <td><?php print $value['item_id']; ?></td>
                    <td class="item_img"><img class="img_size" src=<?php print '../../include/view/images/' . $value['item_img']; ?>></td>
                    <td><?php print $value['item_name']; ?></td>
                    <td><?php print $value['price']; ?></td>

                    <!-- 在庫変更 -->
                    <form method="post">
                        <td><input type="text" class="input_text_width text_align_right" name="update_stock" value=<?php print $value['stock']; ?>>
                            個<br>

                            <button type="submit">変更する</button>
                        </td>
                        <input type="hidden" name="item_id" value=<?php print $value['item_id']; ?>>
                        <input type="hidden" name="sql_kind" value="update">
                    </form>

                    <!-- ステータス変更 -->
                    <form method="post">
                        <td>
                            <button type="submit"><?php (int)$value['status'] === 1 ? print '公開→非公開にする' : print '非公開→公開にする'; ?></button>
                        </td>
                        <input type="hidden" name="change_status" value=<?php (int)$value['status'] === 0 ? print 1 : print 0; ?>>
                        <input type="hidden" name="item_id" value=<?php print $value['item_id']; ?>>
                        <input type="hidden" name="sql_kind" value="change">
                    </form>

                    <!-- 商品削除 -->
                    <form method="post">
                        <td>
                            <button type="submit">削除する</button>
                        </td>
                        <input type="hidden" name="item_id" value=<?php print $value['item_id']; ?>>
                        <input type="hidden" name="item_image" value=<?php print $value['item_img']; ?>>
                        <input type="hidden" name="sql_kind" value="delete">
                    </form>

                </tr>
            <?php } ?>

        </table>
    </section>

</body>

</html>
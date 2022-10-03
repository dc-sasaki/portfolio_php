<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ユーザー一覧</title>
</head>

<body>

    <section>
        <h2>ユーザー一覧</h2>

        <a href="../logout.php">ログアウト</a>
        <a href="../../htdocs/management/item_management.php">商品管理ページ</a>

        <table border="1">
            <tr>

                <th>ユーザー名</th>
                <th>登録日</th>
            </tr>

            <?php foreach ($get_user_all_list as $value) { ?>
                <tr>
                    <td><?php print $value['user_name']; ?></td>
                    <td><?php print $value['user_created_date']; ?></td>
                </tr>
            <?php } ?>



</body>

</html>
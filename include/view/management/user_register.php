<!DOCTYPE HTML>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ユーザー登録</title>
    <link type="text/css" rel="stylesheet" href="../../include/view/css/common.css">
    <link type="text/css" rel="stylesheet" href="../../include/view/css/shop_header.css">
    <link type="text/css" rel="stylesheet" href="../../include/view/css/login_top.css">
</head>

<body>
    <header>
        <div class="container">
            <div class="header-left">
                <h1 class="header-logo">
                    ユーザー登録
                </h1>
            </div>

        </div>
    </header>

    <div class="main">
        <div class="register">

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

            <form method="post">
                <label for="user_name">ユーザー名：</label>
                <input id="user_name" type="text" name="user_name" placeholder="ユーザー名">
                <br>
                <label for="password">パスワード：</label>
                <input id="password" type="text" name="password" placeholder="パスワード">
                <div class="button_wrapper">
                    <button type="submit">ユーザーを新規登録する</button>
                </div>
            </form>


            <a href="../../htdocs/login/login_top.php">ログインページへ戻る</a>


        </div>
    </div>

</body>

</html>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link type="text/css" rel="stylesheet" href="../../include/view/css/common.css">
    <link type="text/css" rel="stylesheet" href="../../include/view/css/shop_header.css">
    <link type="text/css" rel="stylesheet" href="../../include/view/css/login_top.css">
</head>

<body>

    <header>
        <div class="container">
            <div class="header-left">
                <h1 class="header-logo">
                    SHOP
                </h1>
            </div>

        </div>
    </header>

    <div class="main">

        <div class="register">

            <form method="post" action="login.php">
                <label for="user_name">ユーザー名：</label>
                <input id="user_name" type="text" name="user_name" placeholder="ユーザー名">
                <br>
                <label for="password">パスワード：</label>
                <input id="password" type=”password” name="password" placeholder="パスワード" autocomplete="off">
                <div class="button_wrapper">
                    <button type="submit">ログイン</button>
                </div>
            </form>

            <a href="../../htdocs/management/user_register.php">ユーザー登録ページへ進む</a>

            <?php if ($login_err_flag === TRUE) { ?>
                <p class="error">メールアドレス又はパスワードが違います</p>
            <?php } ?>
        </div>
    </div>

</body>

</html>
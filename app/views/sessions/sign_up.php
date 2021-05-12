<?php
require '/var/www/app/helpers/first_actions.php';
require '/var/www/app/helpers/error.php';
if (!empty($_POST)) {
    if (!CsrfValidator::validate(filter_input(INPUT_POST, 'token'))) {
        header('Content-Type: text/plain; charset=UTF-8', true, 400);
        die('CSRF validation failed.');
    }

    $session_controller->do_sign_up($error);
};
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>会員登録</title>

</head>

<body>
    <div id="wrap">
        <div id="head">
            <h1>会員登録</h1>
        </div>

        <div id="content">
            <p>次のフォームに必要事項をご記入ください。</p>
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?= CsrfValidator::generate() ?>">
                <dl>
                    <dt>ニックネーム<span class="required">必須</span></dt>
                    <dd>
                        <input type="text" name="name" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)); ?>" />
                        <?php if ($error['name'] === 'blank') : ?>
                            <p class="error" style="color: red;">name error </p>
                        <?php endif ?>
                    </dd>
                    <dt>メールアドレス<span class="required">必須</span></dt>
                    <dd>
                        <input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)); ?>" />
                        <?php if ($error['email'] === 'blank') : ?>
                            <p class="error" style="color: red;">email blank error </p>
                        <?php elseif ($error['email'] === 'duplicate') : ?>
                            <p class="error" style="color: red;">email duplicate error </p>
                        <?php endif ?>
                    <dt>パスワード<span class="required">必須</span></dt>
                    <dd>
                        <input type="password" name="password" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" />
                        <?php if ($error['password'] === 'blank') : ?>
                            <p class="error" style="color: red;">password error </p>
                        <?php endif ?>
                        <?php if ($error['password'] === 'length') : ?>
                            <p class="error" style="color: red;">password length error </p>
                        <?php endif ?>
                    </dd>
                </dl>
                <div><input type="submit" value="入力内容を確認する" /></div>
            </form>
            <p>&raquo;<a href="sign_in.php">ログインページへ</a></p>
        </div>
</body>

</html>
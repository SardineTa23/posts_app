<?php
  require '../helpers/first_actions.php';
  
  //formが送信されたあと＝ボタンが押された時の処理
  if (!empty($_POST) && $_POST['button'] ==='test_login'){
    $session_controller->click_sign_in_button('a@a.com', sha1('000000'));
  } elseif (!empty($_POST)) { 
    $session_controller->click_sign_in_button($_POST['email'], sha1($_POST['password']));
  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>ログインする</title>
</head>

<body>
    <div id="wrap">
        <div id="head">
            <h1>ログインする</h1>
        </div>
        <div id="content">
            <div id="lead">
                <p>メールアドレスとパスワードを記入してログインしてください。</p>
                <p>入会手続きがまだの方はこちらからどうぞ。</p>
                <p>&raquo;<a href="sign_up.php">入会手続きをする</a></p>
            </div>
            <div>
              <form method='post'><button name='button' value='test_login'>testlogin</button></form>
            </div>
            <form action="" method="post">
                <dl>
                    <dt>メールアドレス</dt>
                    <dd>
                        <input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($email); ?>" />
                        <?php if ($error['login'] === 'blank') : ?>
                            <p class="error"> kara</p>
                        <?php elseif ($error['login'] === 'faild') : ?>
                            <p class="error"> sippai</p>
                        <?php endif ?>
                    </dd>
                    <dt>パスワード</dt>
                    <dd>
                        <input type="password" name="password" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['password']); ?>" />
                    </dd>
                    <dt>ログイン情報の記録</dt>
                    <dd>
                        <input id="save" type="checkbox" name="save" value="on">
                        <label for="save">次回からは自動的にログインする</label>
                    </dd>
                </dl>
                <div>
                    <input type="submit" value="ログインする" />
                </div>
            </form>
        </div>
        <div id="foot">
            <p><img src="images/txt_copyright.png" width="136" height="15" alt="(C) H2O Space. MYCOM" /></p>
        </div>
    </div>
</body>

</html>
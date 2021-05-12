<header>
  <?php if ($_SESSION['message'] !== "") {
    echo $_SESSION['message'];
    $_SESSION['message'] = "";
  }
  ?>
  <div style="display:inline-block"><a href="/">ホーム</a></div>
  <?php if ($current_user) : ?>
    <div style="display:inline-block;"><a href="/?action=sign_out" name="">ログアウト</a></div>
  <?php else : ?>
    <div style="display:inline-block;"><a href="/sign_in.php" name="">ログイン</a></div>
  <?php endif ?>
</header>
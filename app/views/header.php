<header>
  <?php if ($_SESSION['message'] !== "") {
    echo $_SESSION['message'];
    $_SESSION['message'] = "";
  }
  ?>
  <?php if ($current_user) : ?>
    <div style="display:inline-block"><a href="/">ホーム</a></div>
    <div style="display:inline-block;"><a href="/?action=<?php print(htmlspecialchars('sign_out')) ?>" name="">ログアウト</a></div>
  <?php endif ?>
</header>
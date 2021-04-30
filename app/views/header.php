<header>
  <?php if ($_SESSION['message'] !== "") {
    echo $_SESSION['message'];
    $_SESSION['message'] = "";
  }
  ?>
  <?php if ($current_user) : ?>
    <div style="text-align: right"><a href="index.php?action=<?php print(htmlspecialchars('sign_out')) ?>" name="">ログアウト</a></div>
  <?php endif ?>
</header>
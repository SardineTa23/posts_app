<header>
  <?php if ($_SESSION['message'] !== "") {
    echo $_SESSION['message'];
    $_SESSION['message'] = "";
  }
  ?>
  <?php if ($current_user) : ?>
    <div style="display:inline-block"><a href="../views/articles/new.php">記事を作成</a></div>
    <div style="display:inline-block;"><a href="index.php?action=<?php print(htmlspecialchars('sign_out')) ?>" name="">ログアウト</a></div>
  <?php endif ?>
</header>
<header>
  <?php if ($current_user) :?>
    <div style="text-align: right"><a href="index.php?action=<?php print(htmlspecialchars('sign_out'))?>" name="">ログアウト</a></div>
  <?php endif ?>
</header>
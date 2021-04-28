<header>
  <?php if ($current_user) :?>
    <div style="text-align: right"><a method='post' name='a' value='a 'href="index.php?state=<?php print(htmlspecialchars('sign_out'))?>" name="">ログアウト</a></div>
  <?php endif ?>
</header>
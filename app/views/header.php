<?php
  $current_user =  $session_controller->search_user();
  if ($_REQUEST['state'] ==='sign_out') {
      $session_controller->click_sign_out_button();
  }
?>

<header>
  <?php if ($current_user) :?>
    <div style="text-align: right"><a href="index.php?state=<?php print(htmlspecialchars('sign_out'))?>" name="">ログアウト</a></div>
  <?php endif ?>
</header>
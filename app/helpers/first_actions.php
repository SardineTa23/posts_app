<?php
  $session_controller = new Sessions_controller($db, $_SESSION);
  $current_user =  $session_controller->search_user();
  if ($_REQUEST['state'] ==='sign_out') {
    $session_controller->click_sign_out_button();
  }
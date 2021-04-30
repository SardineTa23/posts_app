<?php
session_start();
require 'controllers/sessions_controller.php';
require 'dbconnect.php';
$session_controller  = new Sessions_controller($db, $_SESSION);

// 作成してあるユーザーでログイン
$session_controller->click_sign_in_button('a@a.com', sha1('000000'));

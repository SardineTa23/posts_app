<?php
require_once '/var/www/app/helpers/first_actions.php';
$session_controller->check_sign_in();
$article_controller = new ArticlesController();
$article = $article_controller->destroy($current_user);
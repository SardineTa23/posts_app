<?php
// どの画面でも必要になる同じ処理をまとめたファイル
session_start();
//DBと通信するためのPODインスタンス作成
require_once "/var/www/app/dbconnect.php";
// Session状態を管理するコントローラーふぁいる
require_once "/var/www/app/controllers/sessions_controller.php";
require_once '/var/www/app/controllers/articles_controller.php';
require_once '/var/www/app/helpers/csrf_validator.php';
require_once '/var/www/app/models/article.php';
require_once '/var/www/app/models/tag.php';
require_once '/var/www/app/models/image.php';
require_once '/var/www/app/models/user.php';
require_once '/var/www/app/models/article_tag_relationship.php';


$session_controller = new Sessions_controller($_SESSION);

$model = new User();
// ログイン中のユーザーを返す。していなければNULL
if ($_SESSION['id'] === null) {
    $current_user = null;
} else {
    $current_user =  $model->find($_SESSION['id']);
}

// urlリクエストにaction=sign_outが入ったらログアウト
if ($_REQUEST['action'] === 'sign_out') {
    $session_controller->click_sign_out_button();
}

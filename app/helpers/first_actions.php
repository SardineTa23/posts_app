<?php
// どの画面でも必要になる同じ処理をまとめたファイル
session_start();

// Session状態を管理するコントローラーふぁいる
require_once dirname(__DIR__) . "/controllers/sessions_controller.php";
require_once dirname(__DIR__) . '/controllers/articles_controller.php';
require_once dirname(__DIR__) . '/helpers/csrf_validator.php';
require_once dirname(__DIR__) . '/models/article.php';
require_once dirname(__DIR__) . '/models/tag.php';
require_once dirname(__DIR__) . '/models/image.php';
require_once dirname(__DIR__) . '/models/user.php';
require_once dirname(__DIR__) . '/models/article_tag_relationship.php';


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

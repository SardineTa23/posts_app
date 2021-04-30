<?php
// どの画面でも必要になる同じ処理をまとめたファイル
session_start();
//DBと通信するためのPODインスタンス作成
require_once "../dbconnect.php";
// Session状態を管理するコントローラーふぁいる
require_once "../controllers/sessions_controller.php";
$session_controller = new Sessions_controller($db, $_SESSION);

// ログイン中のユーザーを返す。していなければNULL
$current_user =  $session_controller->search_user();
if ($_REQUEST['action'] === 'sign_out') {
    $session_controller->click_sign_out_button();
}

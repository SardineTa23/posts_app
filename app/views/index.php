<?php
require '../helpers/first_actions.php';
$session_controller->check_sign_in();
$articles_controller = new ArticlesController($db);
$current_page = $articles_controller->pagenate();
$maxPage = $current_page->maxPage;
$page = $current_page->page;
$articles = $articles_controller->index($page);
var_dump($page);
var_dump($maxPage);
?>


<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>

	<link rel="stylesheet" href="../style.css" />
</head>

<body>
	<?php require '/var/www/app/views/layouts/header.php' ?>
	<a href="/articles/new.php">記事を作成する</a>


	<?php require_once '/var/www/app/views/layouts/pagenation.php'?>
</body>

</html>
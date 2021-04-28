<?php 
session_start();
require "../dbconnect.php";
require "../controllers/sessions_controller.php";
require '../helpers/first_actions.php';
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
    <?php require 'header.php'?>
	<div id="wrap">
		<div id="head">
			<h1>会員登録</h1>
		</div>

		<div id="content">
			<p>次のフォームに必要事項をご記入ください。</p>
			<form action="" method="post" enctype="multipart/form-data">
				<dl>
					<dt>ニックネーム<span class="required">必須</span></dt>
					<dd>
						<input type="text" name="name" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)); ?>" />
						<?php if ($error['name'] === 'blank') : ?>
							<p class="error">name error </p>
						<?php endif ?>
					</dd>
					<dt>メールアドレス<span class="required">必須</span></dt>
					<dd>
						<input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)); ?>" />
						<?php if ($error['email'] === 'blank') : ?>
							<p class="error">email blank error </p>
						<?php elseif($error['email'] === 'duplicate') :?>
							<p class="error">email duplicate error </p>
						<?php endif ?>
					<dt>パスワード<span class="required">必須</span></dt>
					<dd>
						<input type="password" name="password" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" />
						<?php if ($error['password'] === 'blank') : ?>
							<p class="error">password error </p>
						<?php endif ?>
						<?php if ($error['password'] === 'length') : ?>
							<p class="error">password length error </p>
						<?php endif ?>
					</dd>
					<dt>写真など</dt>
					<dd>
						<input type="file" name="image" size="35" value="test" />
						<?php if ($error['image'] === 'type') : ?>
							<p class="error">file error </p>
						<?php endif ?>
					</dd>
				</dl>
				<div><input type="submit" value="入力内容を確認する" /></div>
			</form>
		</div>
</body>

</html>
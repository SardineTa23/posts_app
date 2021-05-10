<?php
require '../helpers/first_actions.php';
$session_controller->check_sign_in();
$articles_controller = new ArticlesController($db);
$current_page = $articles_controller->pagenate();
$maxPage = $current_page->maxPage;
$page = $current_page->page;
$articles = $articles_controller->index($page);
?>


<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>posts_app</title>
    <link rel="stylesheet" href="../assets/styles/style.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
</head>

<body>
	<?php require '/var/www/app/views/layouts/header.php' ?>
	<a href="/articles/new.php">記事を作成する</a>
	<div class="container container-m">
		<div class="">
			<?php foreach ($articles as $article) : ?>
				<?php $article = new Article($article);
				$thumbnail = $article->search_thumbnail();
				$thumbnail_url = $article->id . "/" . $thumbnail['url'];
				?>
				<div class="card row col-md-3" style="display: inline-block;">
					<img src="../images/article_images/<?php print(htmlspecialchars($thumbnail_url)) ?>" alt="">
					<div class="card-body">
						<p class="card-text"><?php print(htmlspecialchars($article->title, ENT_QUOTES)) ?></p>
					</div>
				</div>
			<?php endforeach ?>
		</div>
	</div>
	<?php require_once '/var/www/app/views/layouts/pagenation.php' ?>
</body>

</html>
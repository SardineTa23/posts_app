<?php
require '/var/www/app/helpers/first_actions.php';
$session_controller->check_sign_in();
$article_controller = new ArticlesController($db);
$article = $article_controller->show();
$images = $article->images();
$tags = $article->tags();
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>記事詳細</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php require_once '/var/www/app/views/layouts/header.php'; ?>

    <div class="container container-m">
        <div class="card">
            <div class="title">タイトル：<?php print(htmlspecialchars($article->title, ENT_QUOTES)) ?></div>
            <div class="title">本文：<?php print(htmlspecialchars($article->body, ENT_QUOTES)) ?></div>
            <div class="images">
                <?php foreach($article->images() as $image) :?>
                    <img class='card-image' src="../images/article_images/<?php print(htmlspecialchars($article->id))?>/<?php print(htmlspecialchars($image['url'])) ?>" alt="">
                <?php endforeach ?>
            </div>
            <div class="tags">
                <div><p>この記事のタグ</p></div>
                <?php foreach($article->tags() as $tag) :?>
                    <div class="card-tag"><p>・<?php print(htmlspecialchars($tag['name'])) ?></p></div>
                <?php endforeach ?>
            </div>
            <?php if ($article->user_id === $current_user['id']) :?>
                <a href="<?php print(htmlspecialchars($article->id)) ?>/edit">編集する</a>
                <a href="<?php print(htmlspecialchars($article->id)) ?>/destroy">削除する</a>
            <?php endif ?>
        </div>
    </div>
</html>
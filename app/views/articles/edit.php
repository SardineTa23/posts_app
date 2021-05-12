<?php
require_once '/var/www/app/helpers/first_actions.php';
$session_controller->check_sign_in();
$article_controller = new ArticlesController();
$article = $article_controller->edit($current_user);
$title = $article->title;
$body = $article->body;

if (!empty($_POST)) {
    $error = $article->validate();
    $title = $_POST['title'];
    $body = $_POST['body'];

    // エラーがないもないときに、createアクションを走らせる
    if (!$error) {
        $article_controller->update($article, $current_user);
    }
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>記事編集</title>

    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php require_once '/var/www/app/views/layouts/header.php';  ?>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name='function' value='update'>
        <div>
            <?php if ($error['title']) : ?>
                <p style='color: red;'><?php print(htmlspecialchars('タイトルがありません')) ?></p>
            <?php endif ?>
            <label for="">タイトル</label><br>
            <input type="text" name="title" size='50' value="<?php print(htmlspecialchars($title)) ?>" />
        </div><br>

        <div>
            <?php if ($error['body']) : ?>
                <p style='color: red;'><?php print(htmlspecialchars('本文がありません')) ?></p>
            <?php endif ?>
            <label for="">本文</label><br>
            <input type="text" name="body" size='50' value="<?php print(htmlspecialchars($body)) ?>" />
        </div><br>

        <input type="submit" value="Update" />
    </form>

</html>
<?php
require_once '/var/www/app/helpers/first_actions.php';

$article_controller = new ArticlesController($db);
$tags = $article_controller->new($session_controller);

if (!empty($_POST)) {
    // var_dump($_FILES);
    // 複数のタグが選択されたときのために、選択されたタグ情報だけを別の配列として取り出す。
    $selected_tags = array_filter($_POST, function ($key) {
        return strpos($key, 'tag') !== false;
    }, ARRAY_FILTER_USE_KEY);

    // 投稿された写真データを＄POSTから取り出す
    $selected_images = array_filter($_FILES, function ($var, $key) {
        // var_dump($var);
        return (strpos($key, 'image') !== false && $var['name'] !== "");
    }, ARRAY_FILTER_USE_BOTH);

    // 記事作成アクションの取り出し。
    $article = new Article($current_user, $db);
    $error = $article->validate($selected_tags, $selected_images);

    // エラーがないもないときに、createアクションを走らせる
    if (!$error) {
        $article_controller->create($article, $selected_tags, $selected_images);
    }
}
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>記事作成</title>

    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <?php require_once '/var/www/app/views/layouts/header.php';  ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div>
            <?php if ($error['title']) : ?>
                <p style='color: red;'><?php print(htmlspecialchars('タイトルがありません')) ?></p>
            <?php endif ?>
            <label for="">タイトル</label><br>
            <input type="text" name="title" size='50' value="<?php print(htmlspecialchars($_POST['title'])) ?>" />
        </div><br>

        <div>
            <?php if ($error['body']) : ?>
                <p style='color: red;'><?php print(htmlspecialchars('本文がありません')) ?></p>
            <?php endif ?>
            <label for="">本文</label><br>
            <input type="text" name="body" size='50' value="<?php print(htmlspecialchars($_POST['body'])) ?>" />
        </div><br>

        <div>
            <?php if ($error['images']) : ?>
                <p style='color: red;'><?php print(htmlspecialchars('写真が選択されていません')) ?></p>
            <?php endif ?>
            <label for="">写真(一枚目はサムネイルとして扱われます)</label><br>
            <input type="file" name="image1" size="35" value="test" 　accept="image/*" required />
            <input type="file" name="image2" size="35" value="test" 　accept="image/*" />
            <input type="file" name="image3" size="35" value="test" 　accept="image/*" />
        </div><br><br>


        <label for="">該当するタグを選択</label>
        <div>
            <?php if ($error['tags']) : ?>
                <p style='color: red;'><?php print(htmlspecialchars('タグが選択されていません')) ?></p>
            <?php endif ?>
            <?php foreach ($tags as $i => $tag) : ?>
                <label for=""><?php print(htmlspecialchars($tag['name'])) ?></label>
                <input type="checkbox" name='tag<?php print(htmlspecialchars($i)) ?>' value="<?php print(htmlspecialchars($tag['id'])); ?>">
            <?php endforeach ?>
        </div>

        <input type="submit" value="作成" />
    </form>

</html>
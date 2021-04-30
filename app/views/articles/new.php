<?php
require_once '../../helpers/first_actions.php';
require_once '../../models/article.php';
require_once '../../controllers/articles_controller.php';
if(!empty($_POST)) {
  var_dump($_POST);
}
echo __FILE__;
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
  <?php require_once '../header.php' ?>
  <form action="" method="post">
    <div>
      <label for="">title</label>
      <input type="text" name="title" size='50' value="<?php print(htmlspecialchars($_POST['title']))?>" />
    </div>
    
      <input type="submit" value="作成" />
  </form>

</html>
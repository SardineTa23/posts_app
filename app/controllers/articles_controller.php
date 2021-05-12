<?php
require_once '/var/www/app/helpers/article_helper.php';

class ArticlesController
{

    function __construct()
    {
        $this->helper = new ArticleHelper();
    }



    public function new()
    {
        $tag =  new Tag();
        $tags = $tag->getAll();
        return $tags;
    }

    public function create($article, $selected_tags, $selected_images)
    {
        // articleのデータ作成
        $article->create($selected_tags, $selected_images);
        // サムネイル画像のセット
        $article->set_thumbnail($article->id, $article->thumbnail_id);
        header('Location: ../index.php');
        exit();
    }

    public function index()
    {
        $model = new Article();
        $articles = $model->index();
        return $articles;
    }

    public function show()
    {
        $model = new Article();
        $article = $model->find();
        return $article;
    }

    public function edit($current_user)
    {
        $model = new Article();
        $article = $model->find();
        if ($this->helper->check_article_user($article, $current_user)) {
            return $article;
        }
    }

    public function update($article, $current_user)
    {
        if ($this->helper->check_article_user($article, $current_user)) {
            $article->update();
            header('Location: /articles/' . $article->id);
            exit();
        }
    }

    public function destroy($current_user)
    {
        $model = new Article();
        $article = $model->find();
        if ($this->helper->check_article_user($article, $current_user)) {
            $article->destroy();
            $_SESSION['message'] = '削除に成功しました';
            header('Location: /');
            exit();
        }
    }

    public function pagenate($db)
    {
        $page = $_REQUEST['page'];
        if ($page == '') {
            $page = 1;
        };
        $page = max($page, 1);
        $counts = $db->query('SELECT COUNT(*) AS cnt FROM articles');
        $cnt = $counts->fetch();
        $maxPage = ceil($cnt['cnt'] / 10);
        $page = min($page, $maxPage);
        $current_page = new ArrayObject();
        $current_page->page = $page;
        $current_page->maxPage = $maxPage;
        return $current_page;
    }
}

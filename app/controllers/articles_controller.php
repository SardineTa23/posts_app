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
        $article_id = $article->create();

        // article_tag_relationshipのデータ作成
        $article_tag_relationship = new ArticleTagRelationship();
        $article_tag_relationship->create($selected_tags, $article_id);

        $image = new Image();
        $image_id = $image->create($selected_images, $article_id);

        // サムネイル画像のセット
        $article->set_thumbnail($article_id, $image_id);
        header('Location: ../index.php');
        exit();
    }

    public function index($page)
    {
        $model = new Article();
        $articles = $model->index_articles($page);
        return $articles;
    }

    public function show()
    {
        $model = new Article();
        $article = $model->search_article();
        return $article;
    }

    public function edit($current_user)
    {
        $model = new Article();
        $article = $model->search_article();
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
        $article = $model->search_article();
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

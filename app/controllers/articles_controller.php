<?php
class ArticlesController
{
    function __construct($db)
    {
        $this->db = $db;
    }



    public function new($session_controller)
    {
        $session_controller->check_sign_in();
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
        $start = ($page - 1) * 5;
        $articles = $this->db->prepare('SELECT * FROM  articles ORDER BY articles.created_at DESC LIMIT ?, 10');
        $articles->bindParam(1, $start, PDO::PARAM_INT);
        $articles->execute();
        return $articles->fetchAll(PDO::FETCH_ASSOC);
    }

    public function show()
    {
    }

    public function edit()
    {
    }

    public function update()
    {
    }

    public function destroy()
    {
    }

    public function pagenate()
    {
        $page = $_REQUEST['page'];
        if ($page == '') {
            $page = 1;
        };
        $page = max($page, 1);
        $counts = $this->db->query('SELECT COUNT(*) AS cnt FROM articles');
        $cnt = $counts->fetch();
        $maxPage = ceil($cnt['cnt'] / 10);
        $page = min($page, $maxPage);
        $current_page = new ArrayObject();
        $current_page->page = $page;
        $current_page->maxPage = $maxPage;
        return $current_page;
    }
}

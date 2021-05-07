<?php
class ArticlesController
{
    function __construct($db)
    {
        $this->db = $db;
    }



    function new($session_controller)
    {
        $session_controller->check_sign_in();
        $tag =  new Tag($this->db);
        $tags = $tag->getAll();
        return $tags;
    }

    function create($article, $selected_tags, $selected_images)
    {
        // articleのデータ作成
        $article_id = $article->create();

        // article_tag_relationshipのデータ作成
        $article_tag_relationship = new ArticleTagRelationship($this->db);
        $article_tag_relationship->create($selected_tags, $article_id);

        $image = new Image($this->db);
        $image_id = $image->create($selected_images, $article_id);

        // サムネイル画像のセット
        $article->set_thumbnail($article_id, $image_id);
        header('Location: ../index.php');
        exit();
    }

    function show()
    {
    }

    function edit()
    {
    }

    function update()
    {
    }

    function destroy()
    {
    }
}

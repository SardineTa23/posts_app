<?php
require_once 'model.php';
class ArticleTagRelationship extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    function create($article, $selected_tags)
    {
        foreach ($selected_tags as $tag_id) {
            $statement = $article->db->prepare('INSERT INTO article_tag_relationships SET article_id= ?, tag_id= ?');
            $statement->bindValue(1, $article->id);
            $statement->bindValue(2, $tag_id);
            $statement->execute();
        }
    }
}

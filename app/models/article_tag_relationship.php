<?php
require_once 'model.php';
class ArticleTagRelationship extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    function create($selected_tags, $article_id)
    {
        try {
            foreach ($selected_tags as $tag_id) {
                $statement = $this->db->prepare('INSERT INTO article_tag_relationships SET article_id= ?, tag_id= ?');
                $statement->bindValue(1, $article_id);
                $statement->bindValue(2, $tag_id);
                $statement->execute();
            }
        } catch (PDOException $e) {
            print('Error:' . $e->getMessage());
        }
    }
}

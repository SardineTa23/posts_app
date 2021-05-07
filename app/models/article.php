<?php
class Article
{
    function __construct($current_user, $db)
    {
        $this->title = $_POST['title'];
        $this->body = $_POST['body'];
        $this->user_id = $current_user['id'];
        $this->db = $db;
    }

    function validate($selected_tags, $selected_images)
    {
        if ($this->title === "") {
            $error['title'] = 'blank';
        }
        if ($this->body === "") {
            $error['body'] = 'blank';
        }
        if (empty($selected_tags)) {
            $error['tags'] = 'empty';
        }
        if (empty($selected_images)) {
            $error['images'] = 'empty';
        }

        return $error;
    }

    function create()
    {
        $statement = $this->db->prepare('INSERT INTO articles SET title=?, body=?, user_id=?');
        $statement->execute(array(
            $this->title,
            $this->body,
            $this->user_id
        ));
        $this->id = $this->db->lastInsertId();
        return $this->id;
    }

    public function set_thumbnail($article_id, $image_id)
    {
        $article_stm = $this->db->prepare("UPDATE articles SET thumbnail_id = ? WHERE id = ?");
        $article_stm->execute(array($image_id, $article_id));
    }
}

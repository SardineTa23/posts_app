<?php
require_once 'model.php';

class Article extends Model
{
    public ?string $id;
    public ?string $name;
    public ?string $user_id;
    public ?string $thumbnail_id;
    public ?string $body;
    public ?string $title;
    public PDO $db;


    function __construct($obj = [])
    {
        parent::__construct();
        foreach ($obj as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
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

    function set_new_article($current_user)
    {
        $this->title = $_POST['title'];
        $this->body = $_POST['body'];
        $this->user_id = $current_user['id'];
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

    public function search_thumbnail()
    {
        $stm = $this->db->prepare('SELECT * FROM images WHERE id = ?');
        $stm->execute(array($this->thumbnail_id));

        // 見つかったサムネイルのレコードを返す。
        return $stm->fetch();
    }
}

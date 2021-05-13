<?php
require_once 'model.php';

class Article extends Model
{
    public ?string $id;
    public ?string $user_id;
    public ?string $thumbnail_id;
    public ?string $body;
    public ?string $title;


    function __construct($obj = [])
    {
        parent::__construct();
        foreach ($obj as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    function validate($action ,$selected_tags = [], $selected_images = [])
    {

        if ($this->title === "") {
            $error['title'] = 'blank';
        }
        if ($this->body === "") {
            $error['body'] = 'blank';
        }
        if ($action === 'create') {
            if (empty($selected_tags)) {
                $error['tags'] = 'empty';
            }
            if (empty($selected_images)) {
                $error['images'] = 'empty';
            }
        }
        return $error;
    }



    function create($selected_tags, $selected_images)
    {
        $article_tag_relationship = new ArticleTagRelationship();
        $image = new Image();
        try {
            $article_stm = $this->db->prepare('INSERT INTO articles SET title= :title, body= :body, user_id= :user_id');
            $article_stm->bindParam(':title', $this->title);
            $article_stm->bindParam(':body',  $this->body);
            $article_stm->bindParam(':user_id', $this->user_id);
            $article_stm->execute();
            $this->id = $this->db->lastInsertId();
            $article_tag_relationship->create($selected_tags, $this->id);
            $this->thumbnail_id = $image->create($selected_images, $this->id);
            return $this->id;
        } catch (PDOException $e) {
            print('Error:' . $e->getMessage());
        }
    }

    public function set_thumbnail($article_id, $image_id)
    {
        try {
            $stm = $this->db->prepare("UPDATE articles SET thumbnail_id = :image_id WHERE id = :article_id");
            $stm->bindParam(':image_id', $image_id);
            $stm->bindParam(':article_id', $article_id);
            $stm->execute();
        } catch (PDOException $e) {
            var_dump($e);
            exit($e->getMessage());
        }
    }

    public function search_thumbnail()
    {
        try {
            $stm = $this->db->prepare('SELECT * FROM images WHERE id = ?');
            $stm->bindParam(1, $this->thumbnail_id);
            $stm->execute();
        } catch (PDOException $e) {
            var_dump($e);
            exit($e->getMessage());
        }

        // 見つかったサムネイルのレコードを返す。
        return $stm->fetch();
    }

    public function find()
    {
        preg_match('/\d+/', $_SERVER['REQUEST_URI'], $id);
        try {
            $stm = $this->db->prepare('SELECT * FROM articles WHERE id = ?');
            $stm->bindParam(1, $id[0], PDO::PARAM_INT);
            $stm->execute();
            $db_article = $stm->fetch();
        } catch (PDOException $e) {
            var_dump($e);
            exit($e->getMessage());
        }
        if ($db_article) {
            $article = new Article($db_article);
            return $article;
        } else {
            header('Location: ../index.php');
            exit();
        }
    }

    public function index()
    {
        try {
            // 以下ページネーションを使っていたときのコード、現在は使ってないです。
            // $start = ($page - 1) * 10;
            // $articles = $this->db->prepare('SELECT * FROM  articles ORDER BY articles.created_at DESC LIMIT 0, 10');
            // // $articles->bindParam(1, $start, PDO::PARAM_INT);
            // $articles->execute(array($start));


            $articles = $this->db->query('SELECT * FROM articles');
            return  $articles->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            var_dump($e);
            exit($e->getMessage());
        }
    }


    // 紐づくイメージを返す
    public function images()
    {
        try {
            $stm = $this->db->prepare('SELECT images.url FROM images WHERE article_id = :article_id');
            $stm->bindParam(':article_id', $this->id, PDO::PARAM_INT);
            $stm->execute();
            $images = $stm->fetchAll();
            return $images;
        } catch (PDOException $e) {
            var_dump($e);
            exit($e->getMessage());
        }
    }

    public function tags()
    {
        // 中間テーブルを経由して、紐づくタグを取得
        try {
            $stm = $this->db->prepare(
                'SELECT tags.name FROM articles
                INNER JOIN article_tag_relationships
                ON article_tag_relationships.article_id = articles.id
                INNER JOIN tags ON tags.id = article_tag_relationships.tag_id WHERE article_tag_relationships.article_id = :article_id'
            );

            $stm->bindParam(':article_id', $this->id, PDO::PARAM_INT);
            $stm->execute();
            $tags = $stm->fetchAll();
            return $tags;
        } catch (PDOException $e) {
            var_dump($e);
            exit($e->getMessage());
        }
    }

    public function update()
    {
        try {
            $stm = $this->db->prepare("UPDATE articles SET title = :title, body = :body WHERE id = :id");
            $stm->bindParam(':title', $this->title);
            $stm->bindParam(':body', $this->body);
            $stm->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stm->execute();
        } catch (PDOException $e) {
            var_dump($e);
            exit($e->getMessage());
        }
    }

    public function destroy()
    {
        $sqls['article'] = 'DELETE FROM articles WHERE id = :id';
        $sqls['relationships'] = 'DELETE FROM article_tag_relationships WHERE article_id = :id';
        $sqls['images'] = 'DELETE FROM images WHERE article_id = :id';
        $path = "/var/www/app/views/images/article_images/" . $this->id;
        try {
            $this->db->beginTransaction();
            foreach ($sqls as $sql) {
                $stm = $this->db->prepare($sql);
                $stm->bindParam(':id', $this->id, PDO::PARAM_INT);
                $stm->execute();
            }
            $this->db->commit();
            $this->remove_directory($path);
        } catch (Exception $e) {
            $this->db->rollBack();
            exit($e->getMessage());
        }
    }
}

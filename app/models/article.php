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

    function validate($selected_tags = [], $selected_images = [])
    {

        if ($_POST['title'] === "") {
            $error['title'] = 'blank';
        }
        if ($_POST['body'] === "") {
            $error['body'] = 'blank';
        }
        if ($_POST['function'] === 'create') {
            if (empty($selected_tags)) {
                $error['tags'] = 'empty';
            }
            if (empty($selected_images)) {
                $error['images'] = 'empty';
            }
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

    public function search_article()
    {
        preg_match('/\d+/', $_SERVER['REQUEST_URI'], $id);
        $stm = $this->db->prepare('SELECT * FROM articles WHERE id = ?');
        $stm->execute(array($id[0]));
        $article = $stm->fetch();
        if ($article) {
            $article = new Article($article);
            return $article;
        } else {
            header('Location: ../index.php');
            exit();
        }
    }

    public function index_articles($page)
    {
        $start = ($page - 1) * 5;
        $articles = $this->db->prepare('SELECT * FROM  articles ORDER BY articles.created_at DESC LIMIT ?, 10');
        $articles->bindParam(1, $start, PDO::PARAM_INT);
        $articles->execute();
        return  $articles->fetchAll(PDO::FETCH_ASSOC);
    }


    // 紐づくイメージを返す
    public function images()
    {
        $stm = $this->db->prepare('SELECT images.url FROM images WHERE article_id = :article_id');
        $stm->bindParam(':article_id', $this->id, PDO::PARAM_INT);
        $stm->execute();
        $images = $stm->fetchAll();
        return $images;
    }

    public function tags()
    {
        // 中間テーブルを経由して、紐づくタグを取得
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
    }

    public function update() {
        try {
            $stm = $this->db->prepare("UPDATE articles SET title = :title, body = :body WHERE id = :id");
            $stm->bindParam(':title', $_POST['title']);
            $stm->bindParam(':body', $_POST['body']);
            $stm->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stm->execute();
        } catch (PDOException $e) {
            var_dump($e);
            exit($e->getMessage());
        }
    }

    public function destroy() {
        $sqls['article'] = 'DELETE FROM articles WHERE id = :id';
        $sqls['relationships'] = 'DELETE FROM article_tag_relationships WHERE article_id = :id';
        $sqls['images'] = 'DELETE FROM images WHERE article_id = :id';
        $path = "/var/www/app/views/images/article_images/" . $this->id;
        try{
            $this->db->beginTransaction();
            foreach($sqls as $sql) {
                $stm = $this->db->prepare($sql);
                $stm->bindParam(':id', $this->id, PDO::PARAM_INT);
                $stm->execute();
            }
            $this->db->commit();
            function remove_directory($dir) {
                $files = array_diff(scandir($dir), array('.','..'));
                foreach ($files as $file) {
                    // ファイルかディレクトリによって処理を分ける
                    if (is_dir("$dir/$file")) {
                        // ディレクトリなら再度同じ関数を呼び出す
                        remove_directory("$dir/$file");
                    } else {
                        // ファイルなら削除
                        unlink("$dir/$file");
                    }
                }
                // 指定したディレクトリを削除
                return rmdir($dir);
            }
            remove_directory($path);
        }catch(Exception $e){
            $this->db->rollBack(); 
            var_dump($e);
        }
    }
}



<?php
class ArticlesController
{
    function __construct($db)
    {
        $this->db = $db;
    }

    function validate($article, $selected_tags, $selected_images)
    {
        if ($article->title === "") {
            $error['title'] = 'blank';
        }
        if ($article->body === "") {
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

    function new($session_controller)
    {
        $session_controller->check_sign_in();
        $tag =  new Tag($this->db);
        $tags = $tag->getAll();
        return $tags;
    }

    function create($article, $selected_tags, $selected_images)
    {
        $statement = $this->db->prepare('INSERT INTO articles SET title=?, body=?, user_id=?');
        $statement->execute(array(
            $article->title,
            $article->body,
            $article->user_id
        ));
        $data = $statement->fetch();
        var_dump($data);
        // header('Location: ../index.php');
        // exit();
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

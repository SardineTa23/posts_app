<?
session_start();
class ArticleHelper
{
    public function check_article_user($article, $current_user)
    {
        if ($article->user_id === $current_user['id']) {
            return true;
        } else {
            $_SESSION['message'] = '異なるユーザーの投稿は編集できません';
            header('Location: /');
            exit();
        }
    }
}

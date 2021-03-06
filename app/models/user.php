<?php
require_once 'model.php';
class User extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function create(string $name, string $email, string $password)
    {
        try {
            $stm = $this->db->prepare('INSERT INTO users SET name=:name, email=:email, password=:password, profile_image=""');
            $stm->bindParam(':name', $name);
            $stm->bindParam(':email', $email);
            $stm->bindParam(':password', $password);
            $stm->execute();
        } catch (PDOException $e) {
            var_dump($e);
            exit();
        }
    }

    public function find(string $id = '')
    {
        if ($id) {
            try {
                $stm = $this->db->prepare('select * from users where id=:id');
                $stm->bindParam(':id', $id, PDO::PARAM_INT);
                $stm->execute();
                $current_user = $stm->fetch();
                return $current_user;
            } catch (PDOException $e) {
                var_dump($e);
                exit();
            }
        } else {
            return $current_user = null;
        }
    }

    public function find_by(string $email, string $password)
    {
        try {
            $stm = $this->db->prepare('SELECT * FROM users WHERE email= :email');
            $stm->bindParam(':email', $email);
            $stm->execute();
            $result = $stm->fetch();
            if(password_verify($password, $result['password'])){
                $_SESSION['message'] = 'ログイン認証に成功しました';
                return $result;
            }else{
                var_dump($password);
                $_SESSION['message'] = 'ログイン認証に失敗しました';
            } 
        } catch (PDOException $e) {
            var_dump($e);
            exit();
        }
    }
}

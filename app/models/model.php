<?php

class Model
{

    public function __construct()
    {
        try {
            // host=XXXの部分のXXXにはmysqlのサービス名を指定します
            $dsn = 'mysql:host=mysql;dbname=posts_app;charset=utf8';
            $db = new PDO($dsn, 'root', 'password');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
            exit;
        }
        $this->db = $db;
    }

    public function remove_directory($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            // ファイルかディレクトリによって処理を分ける
            if (is_dir("$dir/$file")) {
                // ディレクトリなら再度同じ関数を呼び出す
                $this->remove_directory("$dir/$file");
            } else {
                // ファイルなら削除
                unlink("$dir/$file");
            }
        }
        // 指定したディレクトリを削除
        return rmdir($dir);
    }
}

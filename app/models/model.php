<?php

class Model {

    public function __construct() {
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
}
<?php
try {
    // host=XXXの部分のXXXにはmysqlのサービス名を指定します
    $dsn = 'mysql:host=mysql;dbname=posts_app;';
    $db = new PDO($dsn, 'root', 'password');
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
}

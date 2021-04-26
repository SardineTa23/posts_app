<?php
try {
    // host=XXXの部分のXXXにはmysqlのサービス名を指定します
    $dsn = 'mysql:host=mysql;dbname=posts_app;';
    $db = new PDO($dsn, 'root', 'password');

    $sql = 'SELECT * FROM users;';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    var_dump($result);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit;
} ?>
<?php
if (!empty($_POST)) {
    if ($_POST['name'] === '') {
        $error['name'] = 'blank';
    }
    if ($_POST['email'] === '') {
        $error['email'] = 'blank';
    }
    if (strlen($_POST['password']) < 4) {
        $error['password'] = 'length';
    }
    if ($_POST['password'] === '') {
        $error['password'] = 'blank';
    }
    // $fileName = $_FILES['image']['name'];
    // if (!empty($fileName)) {
    //     $ext = substr($fileName, -3);
    //     if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png' && $ext != 'JPG') {
    //         $error['image'] = 'type';
    //     }
    // }

    // 重複チェック
    if (empty($error)) {
        $member = $db->prepare('SELECT COUNT(*) AS cnt FROM users WHERE email=? ');
        $member->execute(array($_POST["email"]));
        $record = $member->fetch();
        if ($record["cnt"] > 0) {
            $error['email'] = 'duplicate';
        }
    }
}

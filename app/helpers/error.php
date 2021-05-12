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

    // 重複チェック
    if (empty($error)) {
        $user = new User();
        $stm = $user->db->prepare('SELECT COUNT(*) AS cnt FROM users WHERE email=? ');
        $stm->execute(array($_POST["email"]));
        $record = $stm->fetch();
        if ($record["cnt"] > 0) {
            $error['email'] = 'duplicate';
        }
    }
}

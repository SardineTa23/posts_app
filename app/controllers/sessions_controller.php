<?php
class Sessions_controller
{
    // コンストラクタで、接続したPDOインスタンスと現在のセッション情報を受け取る
    function __construct($db, $current_session)
    {
        $this->db = $db;
        $this->session = $current_session;
    }

    // ログイン中のユーザーを返すメソッド
    public function search_user()
    {
        if ($_SESSION['id']) {
            $users = $this->db->prepare('select * from users where id=?');
            $users->execute(array($this->session['id']));
            $current_user = $users->fetch();
            return $current_user;
        } else {
            $current_user = null;
        }

        return $current_user;
    }


    // 現在ユーザーがサインイン中かを確かめるメソッド
    public function check_sign_in()
    {
        // 現在のセッションをDBで検索かける、elseならサインインページへ
        if (isset($this->session['id']) && $this->session['time'] + 3600 > time()) {
            $_SESSION['time'] = time();
            return true;
        } else {
            header('Location: /sessions/sign_in.php');
            exit();
        }
    }

    //現在ログイン中のユーザーがいないかのチェック
    public function check_sign_out()
    {
        // ログインしていないときは返り値がfalseになる、そうでない＝ログインしているときは、index.phpへ遷移
        if ($this->search_user() !== false) {
            header('Location: /');
            exit();
        }
    }

    // ログイン関係のボタンが押されたときのメソッド
    public function click_sign_in_button(string $email, string $password)
    {
        if ($email !== "" && $password !== "") {
            $login = $this->db->prepare('SELECT * FROM users WHERE email=? AND password=?');
            $login->execute(array($email, $password));

            $user = $login->fetch();
            if ($user) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['time'] = time();

                // cookieのチェックが入っている時
                if ($_POST['save'] === 'on') {
                    setcookie('email', $email, time() + 60 * 60 * 24 * 14);
                }
                header('Location: ../index.php');
                exit();
                // 該当するユーザーがない時
            } else {
                $error["login"] = 'faild';
            }
            // フォームが空のとき
        } else {
            $error['login'] = 'blank';
        }
    }

    // ログアウトボタンが押されたときのメソッド
    public function click_sign_out_button()
    {
        $_SESSION = array();
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name() . '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();
        setcookie('email', '', time() - 3600);
        header('Location: sign_in.php');
        exit();
    }


    public function do_sign_up($db, $error)
    {
        if (!empty($_POST)) {
            $email = $_POST['email'];
            $password = sha1($_POST['password']);
            $name = $_POST['name'];
            if (empty($error)) {
                // $image = date('YmdHis') . $_FILES['image']['name'];
                // move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
                // $_SESSION['join']['image'] = $image;
                $statement = $db->prepare('INSERT INTO users SET name=?, email=?, password=?, profile_image=""');

                $statement->execute(array(
                    $name,
                    $email,
                    $password,
                    // $_SESSION['image']
                ));

                // 使い終わったsessionの削除
                // unset($_SESSION);
                $_SESSION['message'] = '会員登録が完了しました';
                $this->click_sign_in_button($email, $password);
            }
        }
    }
}

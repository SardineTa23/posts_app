<?php
class Sessions_controller
{
    // コンストラクタで、接続したPDOインスタンスと現在のセッション情報を受け取る
    function __construct($current_session)
    {
        $this->session = $current_session;
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
        $user = new User();
        // ログインしていないときは返り値がfalseになる、そうでない＝ログインしているときは、index.phpへ遷移
        if ($this->session['id'] !== null) {
            header('Location: /');
            exit();
        }
    }

    // ログイン関係のボタンが押されたときのメソッド
    public function click_sign_in_button(string $email, string $password)
    {
        if ($email !== "" && $password !== "") {
            $model = new User;
            $user = $model->find_by($email, $password);
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
        // セッション変数を空にする
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


    // 会員登録が行われるメソッド
    public function do_sign_up($error)
    {
        if (!empty($_POST)) {
            $email = $_POST['email'];
            $password = sha1($_POST['password']);
            $name = $_POST['name'];
            if (empty($error)) {
                $model = new User();
                $model->create($name, $email, $password);
                $_SESSION['message'] = '会員登録が完了しました';
                $this->click_sign_in_button($email, $password);
            }
        }
    }
}

<?php
class Sessions_controller
{
  // コンストラクタで、接続したPDOインスタンスと現在のセッション情報を受け取る
  function __construct($db, $current_session)
  {
    $this->db = $db;
    $this->session = $current_session;
  }

  public function search_user()
  {
    $users = $this->db->prepare('select * from users where id=?');
    $users->execute(array($this->session['id']));
    $current_user = $users->fetch();
    return $current_user;
  }


  // 現在ユーザーがサインイン中かを確かめるメソッド
  public function check_sign_in()
  {
    // 現在のセッションをDBで検索かける、elseならサインインページへ
    if (isset($this->session['id']) && $this->session['time'] + 3600 > time()) {
      $_SESSION['time'] = time();
      $current_user = $this->search_user();
      return $current_user;
    } else {
      header('Location: sign_in.php');
      exit();
    }
  }

  //現在ログイン中のユーザーがいないかのチェック
  public function check_sign_out()
  {
    // ログインしていないときは返り値がfalseになる、そうでない＝ログインしているときは、index.phpへ遷移
    if ($this->search_user() !== false) {
      header('Location: index.php');
      exit();
    }
  }

  // ログイン関係のボタンが押されたときのメソッド
  public function click_sign_in_button(String $email, String $password)
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

        header('Location: index.php');
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
  public function click_sign_out_button() {
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
}

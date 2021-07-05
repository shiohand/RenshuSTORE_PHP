<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  session_start();

  $_SESSION = array(); // 空の配列を代入
  if (isset($_COOKIE[session_name()]) == true) {
    setcookie(session_name(), '', time()-42000, '/');
  }
  session_destroy();

  $title = 'ログアウト';
  include(D_ROOT.'component/header_admin.php');
?>

<h1>ログアウト完了</h1>

<ul class="common-nav">
  <li>
    <a href="login.php">ログイン</a>
  </li>
</ul>

<?php include(D_ROOT.'component/footer_admin.php') ?>
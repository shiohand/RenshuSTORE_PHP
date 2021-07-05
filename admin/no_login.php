<?php
  require_once($_SERVER['DOCUMENT_ROOT'].'/prophp/common/common.php');
  define('BASE', 'admin');
  sessionStart();

  $title = '未ログイン';
  include(D_ROOT.'component/header_admin.php');
?>

<h1>ログインされていません</h1>

<ul class="common-nav">
  <li>
    <a href="login.php">ログイン</a>
  </li>
</ul>

<?php include(D_ROOT.'component/footer_admin.php') ?>